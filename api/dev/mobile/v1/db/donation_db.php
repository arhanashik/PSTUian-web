<?php

class DonationDb
{
    private $con;
 
    public function __construct()
    {
        require_once dirname(__FILE__) . '/db_connect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function getAll()
    {
        $sql = "SELECT id, name, info, email, reference FROM " . DONATION_TABLE;
        //condition
        $sql = $sql . " WHERE confirmed = 1 AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        $sql = $sql . " LIMIT 100";
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $name, $info, $email, $reference);
    
        $donors = array();
        
        while ($stmt->fetch()) {
            $donor = array();
            $donor['id'] = $id;
            $donor['name'] = $name;
            $donor['info'] = $info;
            $donor['email'] = $email;
            $donor['reference'] = $reference;
          
            array_push($donors, $donor);
        }
 
        return $donors;
    }

    public function get($faculty_id)
    {
        $sql = "SELECT id, short_title, title FROM " . DONATION_TABLE; 
        //condition
        $sql = $sql . " WHERE id = '$faculty_id' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $short_title, $title);

        $faculty = array();
        while ($stmt->fetch()) {
            $faculty['id'] = $id;
            $faculty['short_title'] = $short_title;
            $faculty['title'] = $title;
        }
 
        return $faculty;
    }

    public function insert($name, $info, $email, $reference)
    {
        $sql = "INSERT INTO " . DONATION_TABLE . "(name, info, email, reference) 
        VALUES ('$name', '$info', '$email', '$reference')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . DONATION_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}