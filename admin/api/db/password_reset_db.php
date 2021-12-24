<?php
require_once dirname(__FILE__) . '/db.php';

class PasswordResetDb extends Db
{
    public function __construct()
    {
        parent::__construct(PASSWORD_RESET_TABLE);
    }

    public function insert($user_id, $user_type, $email, $auth_token)
    {
        $sql = "INSERT INTO " . PASSWORD_RESET_TABLE . "(user_id, user_type, email, auth_token) 
        VALUES ('$user_id', '$user_type', '$email', '$auth_token')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $user_id, $user_type, $email, $auth_token)
    {
        $sql = "UPDATE " . PASSWORD_RESET_TABLE . " SET user_id = '$user_id', 
        user_type = '$user_type', email = '$email', auth_token = '$auth_token', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}