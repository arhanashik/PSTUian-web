<?php

class AuthDb
{
    private $con;
 
    public function __construct()
    {
        require_once dirname(__FILE__) . '/db_connect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function getDbConnection() {
        return $this->con;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . AUTH_TABLE;
        //sorting
        $sql = $sql . " ORDER BY id DESC";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
 
        return $list;
    }

    public function get($user_id, $user_type)
    {
        $sql = "SELECT * FROM " . AUTH_TABLE . " WHERE user_id = '$user_id' AND user_type = '$user_type'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
 
        return false;
    }

    public function isValidToken($auth_token)
    {
        $sql = "SELECT id FROM " . AUTH_TABLE . " WHERE auth_token = '$auth_token' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function insert($user_id, $user_type, $auth_token)
    {
        $sql = "INSERT INTO " . AUTH_TABLE . "(user_id, user_type, auth_token) 
        VALUES ('$user_id', '$user_type', '$auth_token')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($user_id, $user_type, $auth_token)
    {
        $sql = "UPDATE " . AUTH_TABLE . " SET auth_token = '$auth_token', deleted = 0, updated_at = NOW() 
        WHERE user_id = '$user_id' AND user_type = '$user_type'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function invalidateAuth($user_id, $user_type)
    {
        $sql = "UPDATE " . AUTH_TABLE . " SET deleted = 1, updated_at = NOW() 
        WHERE user_id = '$user_id' AND user_type = '$user_type'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "UPDATE " . AUTH_TABLE . " set deleted = 1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function deletePermanent($id)
    {
        $sql = "DELETE FROM " . AUTH_TABLE . " WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE " . AUTH_TABLE . " set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}