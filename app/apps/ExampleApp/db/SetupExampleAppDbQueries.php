<?php

namespace Bc\App\Apps\ExampleApp\Db;

use Bc\App\Core\DbExtender;

class SetupExampleAppDbQueries extends DbExtender {

    protected $db;

    public function setup()
    {
        $this->createExampleAppDatabase();
        $this->createImagesIfNotExists();
        $this->createUsagesIfNotExists();
        $this->createUploadersIfNotExists();
        $this->createUsersIfNotExists();
        $this->createUserPermsIfNotExists();
    }

     public function createExampleAppDatabase()
    {
        $this->db->queryExec(
            "CREATE DATABASE IF NOT EXISTS `image_tracker` DEFAULT CHARACTER SET utf8mb4;
            USE `image_tracker`;"
        );
    }

    public function createImagesIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE IF NOT EXISTS `images` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `reporter_id` varchar(11) NOT NULL,
            `name_file` varchar(200) NOT NULL,
            `name_display` varchar(100) NOT NULL,
            `width` varchar(10) NOT NULL,
            `height` varchar(10) NOT NULL,
            `media_hash` varchar(200) NOT NULL,
            `is_deleted` int(1) NOT NULL,
            `date_uploaded` varchar(45) NOT NULL,
            `date_deleted` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    public function createUsagesIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE IF NOT EXISTS `usages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `image_id` varchar(11) NOT NULL,
            `reporter_id` varchar(11) NOT NULL,
            `uploader_id` varchar(11) NOT NULL,
            `name_file` varchar(200) NOT NULL,
            `name_display` varchar(100) NOT NULL,
            `caption` varchar(300) NOT NULL,
            `department` varchar(45) NOT NULL,
            `is_deleted` int(1) NOT NULL,
            `date_uploaded` varchar(45) NOT NULL,
            `date_reported` varchar(45) NOT NULL,
            `date_deleted` varchar(45) NOT NULL,
            `date_modified` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    public function createUploadersIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE IF NOT EXISTS `uploaders` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `reporter_id` varchar(11) NOT NULL,
            `user_id` varchar(11) NOT NULL,
            `name` varchar(70) NOT NULL,
            `is_deleted` int(1) NOT NULL,
            `date_created` varchar(45) NOT NULL,
            `date_deleted` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    public function createUsersIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(200) NOT NULL,
            `email` varchar(100) NOT NULL,
            `password_hash` varchar(300) NOT NULL,
            `ttl` int(11) NOT NULL,
            `token_exp` varchar(45) NOT NULL,
            `password_reset` varchar(100) NOT NULL,
            `is_deleted` int(1) NOT NULL,
            `date_created` varchar(45) NOT NULL,
            `date_deleted` varchar(45) NOT NULL,
            `date_modified` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }

    public function createUserPermsIfNotExists()
    {
        return $this->db->queryExec(
          "CREATE TABLE IF NOT EXISTS `user_perms` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `perm_key` varchar(45) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );
    }
}