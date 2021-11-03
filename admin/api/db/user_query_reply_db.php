<?php
require_once dirname(__FILE__) . '/db.php';

class UserQueryReplyDb extends Db
{
    public function __construct()
    {
        parent::__construct(USER_QUERY_REPLY_TABLE);
    }

    public function getAllByQueryId($query_id)
    {
        //columns to select
        $columns = "uqr.*, a.email AS admin_email";
        //query
        $sql = "SELECT $columns FROM " . USER_QUERY_REPLY_TABLE . " uqr 
        LEFT JOIN " . ADMIN_TABLE . " a ON uqr.admin_id = a.id";
        //condition
        $sql = $sql . " WHERE uqr.query_id = $query_id";
        //sorting
        $sql = $sql . " ORDER BY uqr.created_at ASC";
        return parent::getAll($sql);
    }

    public function insert($query_id, $admin_id, $reply)
    {
        //columns to select
        $columns = "query_id, admin_id, reply";
        $sql = "INSERT INTO " . USER_QUERY_REPLY_TABLE . "($columns) 
        VALUES ('$query_id', '$admin_id', '$reply')";
        
        $stmt = $this->con->prepare($sql);
        if($stmt->execute() && $stmt->affected_rows > 0) {
            return $this->con->insert_id;
        } else return false;
    }
}