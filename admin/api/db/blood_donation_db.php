<?php
require_once dirname(__FILE__) . '/db.php';

class BloodDonationDb extends Db
{
    public function __construct()
    {
        parent::__construct(BLOOD_DONATION_TABLE);
    }

    public function insert($user_id, $user_type, $request_id, $date, $info)
    {
        $sql = "INSERT INTO " . BLOOD_DONATION_TABLE . "(user_id, user_type, request_id, date, info) 
        VALUES ('$user_id', '$user_type', '$request_id', '$date', '$info')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $user_id, $user_type, $request_id, $date, $info)
    {
        $sql = "UPDATE " . BLOOD_DONATION_TABLE . " SET user_id = '$user_id', 
        user_type = '$user_type', request_id = '$request_id', date = '$date', 
        info = '$info', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}