<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class DonationDb extends Db
{
    public function __construct()
    {
        parent::__construct(DONATION_TABLE);
    }

    public function getAll()
    {
        $sql = "SELECT id, name, info, email, reference, created_at FROM " . DONATION_TABLE;
        //condition
        $sql = $sql . " WHERE confirmed = 1 AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        $sql = $sql . " LIMIT 100";
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        return parent::getAll($sql);
    }

    public function insert($name, $info, $email, $reference)
    {
        $sql = "INSERT INTO " . DONATION_TABLE . "(name, info, email, reference) 
        VALUES ('$name', '$info', '$email', '$reference')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }
}