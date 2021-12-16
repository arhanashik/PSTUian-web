<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class NotificationDb extends Db {
    public function __construct()
    {
        parent::__construct(NOTIFICATION_TABLE);
    }

    public function getAll($device_id, $page, $limit)
    {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        //query
        $sql = "SELECT * FROM " . NOTIFICATION_TABLE;
        //condition
        // if($device_id === -1) {
        //     $sql = $sql . " WHERE device_id = 'all'";
        // } else {
        //     $sql = $sql . " WHERE (device_id = 'all' OR device_id = '$device_id')";
        // }
        $sql = $sql . " WHERE (device_id = 'all' OR device_id = '$device_id') AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY id DESC";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
        return parent::getAll($sql);
    }

    public function insert($device_id, $type, $title, $message)
    {
        $sql = "INSERT INTO " . NOTIFICATION_TABLE . "(device_id, type, title, message) 
        VALUES (?, ?, ?, ?)";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ssss', $device_id, $type, $title, $message);
        $stmt->execute();
        return $this->con->insert_id;
    }
}