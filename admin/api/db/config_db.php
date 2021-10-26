<?php
require_once dirname(__FILE__) . '/db.php';

class ConfigDb extends Db
{
 
    public function __construct()
    {
        parent::__construct(CONFIG_TABLE);
    }

    public function getLatest() 
    {
        $sql = "SELECT * FROM " . CONFIG_TABLE;
        $sql .= " WHERE deleted = 0 ";
        $sql .= " ORDER BY id DESC LIMIT 1";
        $list = parent::getAll($sql);
        if(!$list || empty($list)) return false;

        return $list[0];
    }

    public function insert(
        $android_version, 
        $ios_version, 
        $data_refresh_version,
        $api_version, 
        $admin_api_version, 
        $force_refresh,
        $force_update
    )
    {
        $columns = 'android_version, ios_version, data_refresh_version, api_version, 
        admin_api_version, force_refresh, force_update';
        $sql = "INSERT INTO " . CONFIG_TABLE . "($columns) 
        VALUES ('$android_version', '$ios_version', '$data_refresh_version', '$api_version', 
        '$admin_api_version', '$force_refresh', '$force_update')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update(
        $id,
        $android_version, 
        $ios_version, 
        $data_refresh_version,
        $api_version, 
        $admin_api_version, 
        $force_refresh,
        $force_update
    )
    {
        $sql = "UPDATE " . CONFIG_TABLE . " set android_version = '$android_version', 
        ios_version = '$ios_version', data_refresh_version = '$data_refresh_version', 
        api_version = '$api_version', admin_api_version = '$admin_api_version', 
        force_refresh = '$force_refresh', force_update = '$force_update', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}