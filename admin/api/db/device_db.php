<?php
require_once dirname(__FILE__) . '/db.php';

class DeviceDb extends Db
{
    public function __construct()
    {
        parent::__construct(DEVICE_TABLE);
    }

    public function getAllTokens() {
        $sql = "SELECT fcm_token FROM " . DEVICE_TABLE;
        //condition
        $sql = $sql . " WHERE deleted = 0";
 
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if($result->num_rows <= 0) return false;

        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row['fcm_token']);
        }
 
        return $list;
    }

    public function getAllTokenPaged($page, $limit) {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $sql = "SELECT fcm_token FROM " . DEVICE_TABLE;
        // condition
        $sql .= " WHERE deleted = 0";
        // constraints
        $sql .= " LIMIT $limit OFFSET $skip_count";
 
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if($result->num_rows <= 0) return false;

        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row['fcm_token']);
        }
 
        return $list;
    }

    public function getToken($device_id) {
        $sql = "SELECT fcm_token FROM " . DEVICE_TABLE;
        //condition
        $sql = $sql . " WHERE id = '$device_id' AND deleted = 0";
 
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row['fcm_token'];
        }
    }

    public function update(
        $id,
        $fcm_token, 
        $model, 
        $android_version, 
        $ios_version,
        $app_version_code, 
        $app_version_name, 
        $ip_address, 
        $lat, 
        $lng, 
        $locale
    )
    {
        $sql = "UPDATE " . DEVICE_TABLE . " SET fcm_token = '$fcm_token', model = '$model', 
        android_version = '$android_version', ios_version = '$ios_version', 
        app_version_code = '$app_version_code', app_version_name = '$app_version_name', 
        ip_address = '$ip_address', lat = '$lat', lng = '$lng', locale = '$locale', 
        updated_at = NOW() WHERE id = '$id' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}