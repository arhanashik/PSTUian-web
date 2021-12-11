<?php
require_once dirname(__FILE__) . '/db.php';

class DonationDb extends Db
{
    public function __construct()
    {
        parent::__construct(DONATION_TABLE);
    }

    public function insert($name, $info, $email, $reference)
    {
        $sql = "INSERT INTO " . DONATION_TABLE . "(name, info, email, reference) 
        VALUES ('$name', '$info', '$email', '$reference')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $name, $info, $email, $reference)
    {
        $sql = "UPDATE " . DONATION_TABLE . " set name = '$name', info = '$info', 
        email = '$email', reference = '$reference', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}