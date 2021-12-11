<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class BloodDonationReqeuestDb extends Db
{
    public function __construct()
    {
        parent::__construct(BLOOD_DONATION_REQUEST_TABLE);
    }

    public function getAll($page, $limit)
    {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $columns = "bdr.id, bdr.user_id, bdr.user_type, bdr.blood_group, bdr.before_date, 
        bdr.contact, bdr.info, s.name, s.image_url";
        $sql = "SELECT $columns FROM " . BLOOD_DONATION_REQUEST_TABLE . " bdr ";
        // join
        $sql .= " LEFT JOIN " . STUDENT_TABLE . " s ON s.id = bdr.user_id";
        // conditions
        $sql .= " WHERE bdr.confirmed = 1 AND bdr.deleted = 0";
        // sorting
        $sql .= " ORDER BY bdr.created_at DESC";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
 
        return parent::getAll($sql);
    }

    public function getById($id)
    {
        $columns = "bdr.id, bdr.user_id, bdr.user_type, bdr.blood_group, bdr.before_date, 
        bdr.contact, bdr.info, s.name, s.image_url";
        $sql = "SELECT $columns FROM " . BLOOD_DONATION_REQUEST_TABLE . " bdr ";
        // join
        $sql .= " LEFT JOIN " . STUDENT_TABLE . " s ON s.id = bdr.user_id";
        // conditions
        $sql .= " WHERE bdr.id = '$id' AND bdr.deleted = 0";
 
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }

    public function insert($user_id, $user_type, $blood_group, $before_date, $contact, $info)
    {
        $sql = "INSERT INTO " . BLOOD_DONATION_REQUEST_TABLE . "(user_id, user_type, blood_group, before_date, contact, info) 
        VALUES ('$user_id', '$user_type', '$blood_group', '$before_date', '$contact', '$info')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $blood_group, $before_date, $contact, $info)
    {
        $sql = "UPDATE " . BLOOD_DONATION_REQUEST_TABLE . " SET blood_group = '$blood_group', 
        before_date = '$before_date', contact = '$contact', info = '$info', updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}