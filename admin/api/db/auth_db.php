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

        $sql = "SELECT id FROM " . ADMIN_TABLE . " WHERE email = '$email' AND password = '$password'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id);

        $user = array();

        while ($stmt->fetch()) {
            $user['id'] = $id;
            $user['email'] = $email;
        }
 
        return empty($user)? false : $user;
    }
}