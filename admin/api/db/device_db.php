<?php
require_once dirname(__FILE__) . '/db.php';

class DeviceDb extends Db
{
    public function __construct()
    {
        parent::__construct(DEVICE_TABLE);
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