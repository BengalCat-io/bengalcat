<?php

namespace Bc\App\Apps\ExampleApp\Db;

use Bc\App\Core\DbExtender;
use Bc\App\Core\Util;

class ExampleAppDbQueries extends DbExtender {

    public $dbQEvents;
    public $dbQPhotos;
    public $dbQPosts;
    public $dbQPusers;

    public function __construct($dbName, \Bc\App\Core\Core $bc)
    {
        parent::__construct($dbName, $bc);

        $this->setUp($dbName, $bc);
    }

    protected function setup($dbName, $bc)
    {
        $sameDb = $this->getDb();

        foreach (['Images', 'Uploaders', 'Usages', 'Users'] as $queryObj) {
            $dbqobj = 'dbQ' . $queryObj;
            $dbqClass = 'Bc\App\Apps\ExampleApp\Db\ExampleAppDbQueries' . $queryObj;
            $this->$dbqobj = new $dbqClass($dbName, $bc);
            $this->$dbqobj->setDb($sameDb);
        }
    }

    public function insertMatch($data)
    {
        return $this->db->queryInsert("
            INSERT INTO `matches`
            (
                listing_id,
                listing_city,
                listing_state,
                listing_zip_code,
                listing_name,
                candidate_id,
                candidate_city,
                candidate_state,
                candidate_zip_code,
                candidate_name,
                user_id,
                status,
                status_id,
                status_sent_success,
                timestamp,
                match_quality,
                type
            )
            VALUES
            (
                :listing_id,
                :listing_city,
                :listing_state,
                :listing_zip_code,
                :listing_name,
                :candidate_id,
                :candidate_city,
                :candidate_state,
                :candidate_zip_code,
                :candidate_name,
                :user_id,
                :status,
                :status_id,
                :status_sent_success,
                :timestamp,
                :match_quality,
                :type
            );",
            $data
        );
    }

    public function updateMatch($data)
    {
        return $this->db->queryUpdate("
            UPDATE `matches` SET
                listing_id = :listing_id,
                listing_city = :listing_city,
                listing_state = :listing_state,
                listing_zip_code = :listing_zip_code,
                listing_name = :listing_name,
                candidate_id = :candidate_id,
                candidate_city = :candidate_city,
                candidate_state = :candidate_state,
                candidate_zip_code = :candidate_zip_code,
                candidate_name = :candidate_name,
                user_id = :user_id,
                status = :status,
                status_id = :status_id,
                status_sent_success = :status_sent_success,
                timestamp = :timestamp,
                match_quality = :match_quality,
                type = :type
            WHERE
                listing_id = :listing_id
                AND candidate_id = :candidate_id
                AND type = :type;
            ", $data
        );
    }

}