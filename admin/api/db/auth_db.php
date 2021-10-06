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

    public function validate($email, $password)
    {

        $sql = "SELECT id, role FROM " . ADMIN_TABLE . " WHERE email = '$email' AND password = '$password'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $role);

        $user = array();

        while ($stmt->fetch()) {
            $user['id'] = $id;
            $user['email'] = $email;
            $user['role'] = $role;
        }
 
        return empty($user)? false : $user;
    }

    public function insert($email, $password, $role)
    {
        $sql = "INSERT INTO " . ADMIN_TABLE . "(email, password, role) 
        VALUES ('$email', '$password', '$role')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
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
}