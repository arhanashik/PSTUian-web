<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/auth_db.php';
require_once dirname(__FILE__) . '/../constant.php';

class DeviceDb extends Db
{
    public function __construct()
    {
        parent::__construct(DEVICE_TABLE);
    }

    public function getAllByUser($user_id, $user_type, $page, $limit) {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        // columns to select
        $columns = "d.id, d.fcm_token, d.model, d.android_version, d.ios_version, 
        d.app_version_code, d.app_version_name, d.ip_address, d.lat, d.lng, d.locale, 
        a.created_at, a.updated_at";
        // query
        $sql = "SELECT $columns FROM " . DEVICE_TABLE . " d";
        // join table
        $sql .= " CROSS JOIN " . AUTH_TABLE . " a ON a.device_id = d.id";
        // condition
        $sql .= " WHERE (a.user_id = '$user_id' AND a.user_type = '$user_type')";
        $sql .= " AND (a.auth_token IS NOT NULL AND a.deleted = 0)";
        $sql .= " AND d.deleted = 0";
        // sorting
        $sql .= " ORDER BY a.updated_at DESC";
        // constraints
        $sql .= " LIMIT $limit OFFSET $skip_count";
        return parent::getAll($sql);
    }

    public function insert(
        $id,
        $fcm_token, 
        $model, 
        $android_version, 
        $app_version_code, 
        $app_version_name, 
        $ip_address, 
        $lat, 
        $lng, 
        $locale
    )
    {
        $columns = "id, fcm_token, model, android_version, app_version_code, app_version_name, 
        ip_address, lat, lng, locale";
        $sql = "INSERT INTO " . DEVICE_TABLE . "($columns) 
        VALUES ('$id', '$fcm_token', '$model', '$android_version', '$app_version_code', 
        '$app_version_name', '$ip_address', '$lat', '$lng', '$locale')";
        return parent::insertSql($sql);
    }

    public function update(
        $id,
        $fcm_token, 
        $model, 
        $android_version, 
        $app_version_code, 
        $app_version_name, 
        $ip_address, 
        $lat, 
        $lng, 
        $locale
    )
    {
        $sql = "UPDATE " . DEVICE_TABLE . " SET fcm_token = '$fcm_token', model = '$model', 
        android_version = '$android_version', app_version_code = '$app_version_code', 
        app_version_name = '$app_version_name', ip_address = '$ip_address', lat = '$lat', 
        lng = '$lng', locale = '$locale', updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        return parent::executeSql($sql);
    }

    public function update_fcm_token($id, $fcm_token)
    {
        $sql = "UPDATE " . DEVICE_TABLE . " SET fcm_token = '$fcm_token', updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        return parent::executeSql($sql);
    }
}