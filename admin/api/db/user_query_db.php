<?php
require_once dirname(__FILE__) . '/db.php';

class UserQueryDb extends Db
{
    public function __construct()
    {
        parent::__construct(USER_QUERY_TABLE);
    }

    public function insert($name, $email, $type, $query)
    {
        //columns to select
        $columns = "name, email, type, query";
        $sql = "INSERT INTO " . USER_QUERY_TABLE . "($columns) 
        VALUES ('$name', '$email', '$type', '$query')";
        
        $stmt = $this->con->prepare($sql);
        if($stmt->execute() && $stmt->affected_rows > 0) {
            return $this->con->insert_id;
        } else return false;
    }
}