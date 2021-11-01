<?php
require_once dirname(__FILE__) . '/db.php';

class LogDb extends Db {
    public function __construct()
    {
        parent::__construct(LOG_TABLE);
    }

    public function insert($logger_id, $logger_type, $action, $data)
    {
        $sql = "INSERT INTO " . LOG_TABLE . "(logger_id, logger_type, action, data) 
        VALUES (?, ?, ?, ?)";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param('ssss', $logger_id, $logger_type, $action, $data);
        $stmt->execute();
        return $this->con->insert_id;
    }
}