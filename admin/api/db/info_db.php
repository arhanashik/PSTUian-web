<?php
require_once dirname(__FILE__) . '/db.php';

class InfoDb extends Db
{
    public function __construct()
    {
        parent::__construct(INFO_TABLE);
    }

    public function insert($donation_option)
    {
        $sql = "INSERT INTO " . INFO_TABLE . "(donation_option) 
        VALUES ('$donation_option')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $donation_option)
    {
        $sql = "UPDATE " . INFO_TABLE . " set donation_option = '$donation_option', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}