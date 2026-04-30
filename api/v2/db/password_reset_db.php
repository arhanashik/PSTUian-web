<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class PasswordResetDb extends Db
{
    public function __construct()
    {
        parent::__construct(PASSWORD_RESET_TABLE);
    }

    public function getByUserIdAndType($user_id, $user_type)
    {
        $sql = "SELECT * FROM " . PASSWORD_RESET_TABLE . " WHERE user_id = '$user_id' AND user_type = '$user_type'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows < 0) return false;

        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }

    // Token is valid if it exists in database and updated within last 30 minutes
    public function isValidToken($user_id, $user_type, $auth_token)
    {
        $timestamp = time();
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('Asia/Tokyo'));
        $dt->setTimestamp($timestamp);
        $validity_time_end = $dt->format('Y-m-d H:i:s');
        $dt->modify('-1 day');
        $validity_time_start = $dt->format('Y-m-d H:i:s');
        
        $condition_valid_user = "((user_id = '$user_id' AND user_type = '$user_type') AND 
        (auth_token = '$auth_token' AND deleted = 0))";
        $condition_within_time = "(updated_at >= '$validity_time_start' AND updated_at <= '$validity_time_end')";
        $sql = "SELECT id FROM " . PASSWORD_RESET_TABLE . " WHERE $condition_valid_user AND $condition_within_time";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function insert($user_id, $user_type, $email, $auth_token)
    {
        $sql = "INSERT INTO " . PASSWORD_RESET_TABLE . "(user_id, user_type, email, auth_token) 
        VALUES ('$user_id', '$user_type', '$email', '$auth_token')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($user_id, $user_type, $email, $auth_token)
    {
        $sql = "UPDATE " . PASSWORD_RESET_TABLE . " SET email = '$email', auth_token = '$auth_token', deleted = 0, updated_at = NOW() 
        WHERE user_id = '$user_id' AND user_type = '$user_type'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function invalidateToken($user_id, $user_type)
    {
        $sql = "UPDATE " . PASSWORD_RESET_TABLE . " SET deleted = 1, updated_at = NOW() 
        WHERE user_id = '$user_id' AND user_type = '$user_type'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}