<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class NotificationDb extends Db {
    public function __construct()
    {
        parent::__construct(NOTIFICATION_TABLE);
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