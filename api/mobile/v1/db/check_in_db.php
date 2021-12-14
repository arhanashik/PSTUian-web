<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class CheckInDb extends Db
{
    private $columns = "ci.id, ci.location_id, ci.user_id, ci.user_type, ci.count,
     ci.visibility, ci.created_at, cil.name as location_name, s.name, s.phone, s.image_url, 
     b.name as batch";
    private $select_query;

    public function __construct()
    {
        parent::__construct(CHECK_IN_TABLE);

        // create select query for the table
        $this->select_query = "SELECT $this->columns FROM " . CHECK_IN_TABLE . " ci ";
        // join
        $this->select_query .= " LEFT JOIN " . CHECK_IN_LOCATION_TABLE . " cil ON cil.id = ci.location_id";
        $this->select_query .= " LEFT JOIN " . STUDENT_TABLE . " s ON s.id = ci.user_id";
        $this->select_query .= " LEFT JOIN " . BATCH_TABLE . " b ON b.id = s.batch_id";
    }

    public function getAllByLocation($location_id, $page, $limit)
    {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE ci.location_id = '$location_id' AND (ci.visibility = 'public' AND ci.deleted = 0)";
        // sorting
        $sql .= " ORDER BY ci.created_at DESC";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
 
        return parent::getAll($sql);
    }

    public function getAllByUser($user_id, $user_type, $page, $limit)
    {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE (ci.user_id = '$user_id' AND ci.user_type = '$user_type') AND ci.deleted = 0";
        // sorting
        $sql .= " ORDER BY ci.created_at DESC";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
 
        return parent::getAll($sql);
    }

    public function getByUser($location_id, $user_id, $user_type)
    {
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE (ci.location_id = '$location_id' AND ci.deleted = 0)";
        $sql .= " AND (ci.user_id = '$user_id' AND ci.user_type = '$user_type')";
        // sorting
        $sql .= " ORDER BY ci.created_at DESC";
        return parent::getSql($sql);
    }

    public function getCheckIn($id)
    {
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE ci.id = '$id' AND ci.deleted = 0";
        return parent::getSql($sql);
    }

    public function insert($location_id, $user_id, $user_type)
    {
        $sql = "INSERT INTO " . CHECK_IN_TABLE . "(location_id, user_id, user_type) 
        VALUES ('$location_id', '$user_id', '$user_type')";
        return parent::insertSql($sql);
    }

    public function update($id, $visibility)
    {
        $sql = "UPDATE " . CHECK_IN_TABLE . " SET visibility = '$visibility', updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        return parent::executeSql($sql);
    }

    public function incrementCount($id)
    {
        $sql = "UPDATE " . CHECK_IN_TABLE . " SET count = count + 1, updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        return parent::executeSql($sql);
    }
}