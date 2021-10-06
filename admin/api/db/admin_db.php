<?php

class AdminDb
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
        $sql = "SELECT * FROM " . ADMIN_TABLE;
        //sorting
        $sql = $sql . " ORDER BY id";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
 
        return $list;
    }

    public function insert($email, $password, $role)
    {
        $sql = "INSERT INTO " . ADMIN_TABLE . "(email, password, role) 
        VALUES ('$email', '$password', '$role')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $email, $password, $role)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " set id = '$id', email = '$email', 
        password = '$password', role = '$role', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function updatePassword($id, $old_password, $new_password)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " SET `password` = '$new_password', updated_at = NOW() 
        WHERE id = '$id' AND password = '$old_password'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function updateRole($id, $role)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " set role = '$role', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " set deleted = 1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function deletePermanent($id)
    {
        $sql = "DELETE FROM " . ADMIN_TABLE . " WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}