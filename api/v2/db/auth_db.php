<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class AuthDb extends Db
{
    public function __construct()
    {
        parent::__construct(AUTH_TABLE);
    }

    public function getByUserIdTypeAndDevice($auth_user_id, $user_type, $device_id)
    {
        $sql = "SELECT * FROM " . AUTH_TABLE . " WHERE (auth_user_id = '$auth_user_id' AND 
        user_type = '$user_type') AND (device_id = '$device_id' AND deleted = 0)";
        return parent::getSql($sql);
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

    public function isValidTokenForUser($auth_user_id, $user_type, $auth_token)
    {
        $sql = "SELECT id FROM " . AUTH_TABLE;
        $sql .= " WHERE (auth_user_id = '$auth_user_id' AND user_type = '$user_type')";
        $sql .= " AND (auth_token = '$auth_token' AND deleted = 0)";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function insert($auth_user_id, $user_type, $auth_token, $device_id)
    {
        $sql = "INSERT INTO " . AUTH_TABLE . "(auth_user_id, device_id, user_type, auth_token) 
        VALUES ('$auth_user_id', '$device_id', '$user_type', '$auth_token')";
        return parent::insertSql($sql);
    }

    public function update($auth_user_id, $user_type, $auth_token, $device_id)
    {
        $sql = "UPDATE " . AUTH_TABLE . " SET auth_token = '$auth_token', updated_at = NOW() 
        WHERE (auth_user_id = '$auth_user_id' AND user_type = '$user_type') AND 
        (device_id = '$device_id' AND deleted = 0)";
        return parent::executeSql($sql);
    }

    public function invalidateAuth($auth_user_id, $user_type, $device_id)
    {
        $sql = "UPDATE " . AUTH_TABLE . " SET auth_token = null, updated_at = NOW() 
        WHERE (auth_user_id = '$auth_user_id' AND user_type = '$user_type') AND 
        (device_id = '$device_id' AND deleted = 0)";
        return parent::executeSql($sql);
    }

    public function invalidateAllAuth($auth_user_id, $user_type)
    {
        $sql = "UPDATE " . AUTH_TABLE . " SET auth_token = null, updated_at = NOW() 
        WHERE (user_id = '$auth_user_id' AND user_type = '$user_type') AND 
        (auth_token IS NOT NULL AND deleted = 0)";
        return parent::executeSqlCount($sql);
    }
}