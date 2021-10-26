<?php
require_once dirname(__FILE__) . '/db.php';

class AdminDb extends Db
{
    public function __construct()
    {
        parent::__construct(ADMIN_TABLE);
    }

    public function validate($email, $password)
    {

        $sql = "SELECT id, role FROM " . ADMIN_TABLE;
        $sql = $sql . " WHERE (email = '$email' AND password = '$password') AND deleted = 0";
        
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

    public function update($id, $email, $password, $role)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " set id = '$id', email = '$email', 
        password = '$password', role = '$role', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function updatePassword($id, $old_password, $new_password)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " SET `password` = '$new_password', updated_at = NOW() 
        WHERE id = '$id' AND password = '$old_password'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function updateRole($id, $role)
    {
        $sql = "UPDATE " . ADMIN_TABLE . " set role = '$role', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}