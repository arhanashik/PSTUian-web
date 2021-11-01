<?php
require_once dirname(__FILE__) . '/db.php';

class NotificationDb extends Db
{
    public function __construct()
    {
        parent::__construct(NOTIFICATION_TABLE);
    }

    public function insert($device_id, $type, $title, $message, $data)
    {
        $sql = "INSERT INTO " . NOTIFICATION_TABLE . "(device_id, type, title, message, data) 
        VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('sssss', $device_id, $type, $title, $message, $data);
        $stmt->execute();
        return $this->con->insert_id;
    }
}