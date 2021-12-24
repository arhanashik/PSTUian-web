<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class BloodDonationDb extends Db
{
    private $columns = "bd.id, bd.user_id, bd.user_type, bd.request_id, bd.date, 
    bd.info, s.name, s.image_url";
    private $select_query;


    public function __construct()
    {
        parent::__construct(BLOOD_DONATION_TABLE);

        // create select query for the table
        $this->select_query = "SELECT $this->columns FROM " . BLOOD_DONATION_TABLE . " bd ";
        // join
        $this->select_query .= " LEFT JOIN " . STUDENT_TABLE . " s ON s.id = bd.user_id";
    }

    public function getAll($user_id, $user_type, $page, $limit)
    {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE (bd.user_id = '$user_id' AND bd.user_type = '$user_type') AND bd.deleted = 0";
        // sorting
        $sql .= " ORDER BY bd.created_at DESC";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
 
        return parent::getAll($sql);
    }

    public function getById($id)
    {
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE bd.id = '$id' AND bd.deleted = 0";
 
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }

    public function insert($user_id, $user_type, $request_id, $date, $info)
    {
        $sql = "INSERT INTO " . BLOOD_DONATION_TABLE . "(user_id, user_type, request_id, date, info) 
        VALUES ('$user_id', '$user_type', '$request_id', '$date', '$info')";
        return parent::insertSql($sql);
    }

    public function update($id, $request_id, $date, $info)
    {
        $sql = "UPDATE " . BLOOD_DONATION_TABLE . " SET request_id = '$request_id', 
        date = '$date', info = '$info', updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        return parent::executeSql($sql);
    }
}