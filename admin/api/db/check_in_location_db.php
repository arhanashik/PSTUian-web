<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class CheckInLocationDb extends Db
{
    private $columns = "id, user_id, user_type, name, details, image_url, link, verified, count";
    private $select_query;

    public function __construct()
    {
        parent::__construct(CHECK_IN_LOCATION_TABLE);

        // create select query for the table
        $this->select_query = "SELECT $this->columns FROM " . CHECK_IN_LOCATION_TABLE;
    }

    public function search($query, $page, $limit)
    {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE deleted = 0 AND name LIKE '%" . $query . "%'";
        // sorting
        $sql .= " ORDER BY count DESC";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
 
        return parent::getAll($sql);
    }

    public function getByName($name)
    {
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE name = '$name' AND deleted = 0";
        return parent::getSql($sql);
    }

    public function insert($user_id, $user_type, $name, $details, $image_url, $link)
    {
        $sql = "INSERT INTO " . CHECK_IN_LOCATION_TABLE . "(user_id, user_type, name, details, image_url, link) 
        VALUES ('$user_id', '$user_type', '$name', '$details', '$image_url', '$link')";
        return parent::insertSql($sql);
    }

    public function update($id, $user_id, $user_type, $name, $details, $image_url, $link, $count)
    {
        $sql = "UPDATE " . CHECK_IN_LOCATION_TABLE . " SET user_id = '$user_id', 
        user_type = '$user_type', name = '$name', details = '$details', 
        image_url = '$image_url', link = '$link', count = '$count', updated_at = NOW() 
        WHERE id = '$id'";
        return parent::executeSql($sql);
    }

    public function updateVerification($id, $verified)
    {
        $sql = "UPDATE $this->table set verified = '$verified', updated_at = NOW() 
        WHERE id = '$id'";
        return parent::executeSql($sql);
    }

    public function incrementCount($id)
    {
        $sql = "UPDATE " . CHECK_IN_LOCATION_TABLE . " SET count = count + 1, updated_at = NOW() 
        WHERE id = '$id'";
        return parent::executeSql($sql);
    }
}