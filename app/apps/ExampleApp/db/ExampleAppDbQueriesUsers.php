<?php

namespace Bc\App\Apps\ExampleApp\Db;

use Bc\App\Core\Util;

class ExampleAppDbQueriesUsers extends ExampleAppDbExtender {

    public function selectAllUsers()
    {
        return $this->db->querySelect(
            "SELECT * from users;"
        );

    }

    public function selectAllUsersCount()
    {
        return $this->db->querySelect(
            "SELECT count(*) as count from users;"
        );

    }

    public function selectUsersByFilters($data = [])
    {
        $data = (array) $data;

        $this->setOrderbyFromData($data);
        $this->setLimitFromData($data);

        $this->addWhereByKey('is_deleted', $data);
        $this->addWhereByKey('email', $data);
        $this->addWhereByKey('id', $data);
        $this->addWhereByKey('ttl', $data);
        $this->addWhereByKey('name', $data);

        return $this->db->querySelect("
            SELECT u.* from users u
                {$this->getWhereToSqlString('AND', true)}
                {$this->getOrderbyToSqlString()}
                {$this->getLimitToSqlString()};
           ", $this->formatDataKeys($data)
        );
    }

    public function insertUser($data = [])
    {
        return $this->db->queryInsert("
            INSERT INTO `users`
            (
                `name`,
                `email`,
                `password_hash`,
                `ttl`,
                `token_exp`,
                `password_reset`,
                `is_deleted`,
                `date_created`,
                `date_deleted`,
                `date_modified`
            )
            values
            (
                :name,
                :email,
                :password_hash,
                :ttl,
                :token_exp,
                :password_reset,
                :is_deleted,
                :date_created,
                :date_deleted,
                :date_modified
            );", $this->formatDataKeys($data)
        );
    }

    /**
     * Pass $data['perm_keys'] with all permissions.
     *
     * @param type $data
     * @return type
     */
    public function insertUserPerms($data = [])
    {
        if (
            !isset($data['perm_keys']) ||
            !is_array($data['perm_keys']) ||
            empty($data['perm_keys'])
        ) {
            return false;
        }

        $valueSql = [];
        $multiValueInsert = "
            INSERT INTO `user_perms`
            (
                `user_id`,
                `perm_key`
            )
            values ";

        foreach ($data['perm_keys'] as $permKey) {
            $uniqueKey = str_replace('-', '_', Util::slugify($permKey));
            $data['perm_key_' . $uniqueKey] = $permKey;
            $valueSql[] = "
            (
                :user_id,
                :perm_key_{$uniqueKey}
            )";
        }

        $multiValueInsert = $multiValueInsert . implode(', ', $valueSql) . ';';

        $this->db->queryInsert($multiValueInsert, $this->formatDataKeys($data));

        return true;
    }

    public function deleteUserPerms($data = [])
    {
        return $this->db->queryDelete(
            "DELETE FROM user_perms WHERE user_id = :user_id;",
            $this->formatDataKeys($data)
        );
    }

    public function selectUserByEmail($data = [])
    {
        return $this->db->querySelect(
            "SELECT * from users where email = :email;", $this->formatDataKeys($data)
        );
    }

    public function selectPermsByUserId($data = [])
    {
        return $this->db->querySelect(
            "SELECT * from user_perms where user_id = :user_id;",
            $this->formatDataKeys($data)
        );
    }
}