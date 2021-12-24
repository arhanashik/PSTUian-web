<?php
require_once dirname(__FILE__) . '/db.php';

class BloodDonationReqeuestDb extends Db
{
    public function __construct()
    {
        parent::__construct(BLOOD_DONATION_REQUEST_TABLE);
    }

    public function insert($user_id, $user_type, $blood_group, $before_date, $contact, $info)
    {
        $sql = "INSERT INTO " . BLOOD_DONATION_REQUEST_TABLE . "(user_id, user_type, blood_group, before_date, contact, info) 
        VALUES ('$user_id', '$user_type', '$blood_group', '$before_date', '$contact', '$info')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $user_id, $user_type, $blood_group, $before_date, $contact, $info)
    {
        $sql = "UPDATE " . BLOOD_DONATION_REQUEST_TABLE . " SET user_id = '$user_id', 
        user_type = '$user_type', blood_group = '$blood_group', before_date = '$before_date', 
        contact = '$contact', info = '$info', updated_at = NOW() 
        WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}