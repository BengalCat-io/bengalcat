<?php

namespace Bc\App\Apps\ExampleApp\Api;

use Bc\App\Core\Util;
use Bc\App\Apps\ExampleApp\ExampleAppUtil;

abstract class ExampleAppApiUser extends ExampleAppApi {

    protected function getUsers($filters)
    {
        if (empty($filters)) {
            $users = $this->itQueries->dbQUsers->selectAllUsers();
        } else {
            $users = $this->itQueries->dbQUsers->selectUsersByFilters($filters);
        }

        if (empty($users)) {
            return $users;
        }

        // If format header is long, get permissions.
        if (isset($this->headers['Format']) && $this->headers['Format'] == 'long') {
            $users = $this->getPermsOnUsers($users);
        }

        return $users;
    }

    protected function getPermsOnUsers($users)
    {
        if (empty($users)) {
            return $users;
        }

        foreach ($users as &$user) {
            $user['user_id'] = $user['id'];
            $perms = $this->itQueries->dbQUsers->selectPermsByUserId($user);

            if (empty($perms)) {
                $user['perms'] = [];
            }

            $user['perms'] = array_reduce($perms, function($carry, $perm){
                $carry[] = $perm['perm_key'];
                return $carry;
            }, []);
        }

        unset($user);

        return $users;
    }

    protected function getUserByEmail($data)
    {
        return $this->itQueries->dbQUsers->selectUserByEmail($data);
    }

    protected function addUser($data)
    {
        $validate = $this->validateAddUser($data);
        if (!$validate->success) {
            return $validate;
        }

        // Use build data from now on.
        $data = $this->buildAddUser($data);

        // Check that email is not already used.
        if (!empty($this->getUserByEmail($data))) {
            $this->triggerError(409, "Email address already registered.");
        }

        // Do any users exist yet:
        $usersExist = current($this->itQueries->dbQUsers->selectAllUsersCount())['count'];

        // Actually add the user
        $this->itQueries->dbQUsers->beginTransaction();
        $this->itQueries->dbQUsers->insertUser($data);

        // Select same user (need ID)
        $newUser = current($this->itQueries->dbQUsers->selectUserByEmail($data));

        if (empty($newUser) || !$newUser) {
            $this->itQueries->dbQUsers->rollBack();
            $this->triggerError(500, "The user was not created due to server / database error. Try again.");
        }
        
        // Send verify email
        $this->sendEmail([
            "type"       => "verify-email",
            "siteUrl"    => Util::getBasePath(),
            "siteName"   => "ExampleApp",
            "name"       => $newUser->name,
            "name_first" => ExampleAppUtil::splitName($newUser->name, 'nameFirst'),
            "name_last"  => ExampleAppUtil::splitName($newUser->name, 'nameLast'),
            "email"      => $newUser->email,
            "bcc"        => [],
            "cc"         => [],
            "data"       => [
                "verifyLink"    => "http://asdasdsds",
                "verifyDisplay" => "asdasds"
            ]
        ]);

        // Give first user ever all permissions.
        if (!$usersExist) {
            $result = $this->addUserPerms($newUser, $this->settings->permissions);
            $user = current($this->getPermsOnUsers([$newUser]));

            if (!$result || !$user || empty($user['perms'])) {
                $this->itQueries->dbQUsers->rollBack();
                $this->triggerError(500, "The user permissions were not set due to server / database error so the user was not added. Try again.");
            }
        }

        $this->itQueries->dbQUsers->commit();

        return (object) ['success' => true];
    }

    protected function addUserPerms($user, $perms)
    {
        $data = [
            'user_id' => $user['id'],
            'perm_keys' => $perms
        ];

        return $this->itQueries->dbQUsers->insertUserPerms($data);
    }

    protected function replaceUserPerms($user, $perms)
    {
        $data = ['user_id' => $user['id']];
        $this->itQueries->dbQUsers->deleteUserPerms($data);
        $this->addUserPerms($user, $perms);
    }

    protected function validateAddUser($data)
    {
        // Check the request data sent over is complete.
        $requestComplete = $this->validateService->validateForm($data, 'SignUp');

        if (!$requestComplete->success) {
            return $requestComplete;
        }

        // Build the add user object with auto fields etc.
        $data = $this->buildAddUser($data);

        // Check that the final user data is complete for insertion
        $buildComplete = $this->validateService->validateData(
                (object) $data, 'AddUser');

        if (!$buildComplete) {
            return $buildComplete;
        }

        // Return built data.
        return (object) ['success' => true];
    }

    protected function buildAddUser($data)
    {
        return [
            'name'           => $data->name,
            'email'          => $data->email,
            'password_hash'  => '',
            'ttl'            => ExampleAppUtil::objectValue(
                                'ttl', $data, $this->settings->ttlDefault),
            'token_exp'      => '',
            'password_reset' => ExampleAppUtil::generatePasswordResetKey(),
            'is_deleted'     => 0,
            'date_created'   => strtotime('now'),
            'date_deleted'   => '',
            'date_modified'  => strtotime('now'),
        ];
    }
}