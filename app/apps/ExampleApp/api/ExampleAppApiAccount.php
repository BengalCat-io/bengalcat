<?php

namespace Bc\App\Apps\ExampleApp\Api;

use Bc\App\Core\Util;
use Bc\App\Apps\ExampleApp\ExampleAppUtil;

abstract class ExampleAppApiAccount extends ExampleAppApi {

    protected function getAccounts($filters)
    {
        if (empty($filters)) {
            $users = $this->itQueries->dbQAccounts->selectAllAccounts();
        } else {
            $users = $this->itQueries->dbQAccounts->selectAccountsByFilters($filters);
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

    protected function getAccountByName($data)
    {
        return $this->itQueries->dbQAccounts->selectAccountByName($data);
    }

    protected function addAccount($data)
    {
        $validate = $this->validateAddAccount($data);
        if (!$validate->success) {
            return $validate;
        }

        // Use build data from now on.
        $data = $this->buildAddAccount($data);

        // Check that email is not already used.
        if (!empty($this->getAccountByName($data))) {
            $this->triggerError(409, "Account Name already registered.");
        }

        // Actually add the user
        $this->itQueries->dbQAccounts->beginTransaction();
        $this->itQueries->dbQAccounts->insertAccount($data);

        // Select same user (need ID)
        $newAccount = current($this->itQueries->dbQAccounts->selectAccountByName($data));

        if (empty($newAccount) || !$newAccount) {
            $this->itQueries->dbQAccounts->rollBack();
            $this->triggerError(500, "The account was not created due to server / database error. Try again.");
        }

        $this->itQueries->dbQAccounts->commit();

        return (object) ['success' => true];
    }

    protected function addUserPerms($user, $perms)
    {
        $data = [
            'user_id' => $user['id'],
            'perm_keys' => $perms
        ];

        return $this->itQueries->dbQAccounts->insertUserPerms($data);
    }

    protected function replaceUserPerms($user, $perms)
    {
        $data = ['user_id' => $user['id']];
        $this->itQueries->dbQAccounts->deleteUserPerms($data);
        $this->addUserPerms($user, $perms);
    }

    protected function validateAddAccount($data)
    {
        // Check the request data sent over is complete.
        $requestComplete = $this->validateService->validateForm($data, 'AccountSignUp');

        if (!$requestComplete->success) {
            return $requestComplete;
        }

        // Build the add user object with auto fields etc.
        $data = $this->buildAddUser($data);

        // Check that the final user data is complete for insertion
        $buildComplete = $this->validateService->validateData(
                (object) $data, 'AddAccount');

        if (!$buildComplete) {
            return $buildComplete;
        }

        // Return built data.
        return (object) ['success' => true];
    }

    protected function buildAddAccount($data)
    {
        return [
            'name'           => $data->name,
            'organization'   => $data->organization,
            'industry'       => $data->industry,
            'phone'          => $data->phone,
            'is_deleted'     => 0,
            'date_created'   => strtotime('now'),
            'date_deleted'   => '',
            'date_modified'  => strtotime('now'),
        ];
    }
}