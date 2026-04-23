<?php

class InfoDb
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
        $sql = "SELECT id, donation_option FROM " . INFO_TABLE;
        //condition
        $sql = $sql . " WHERE deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $donation_option);
    
        $info_list = array();
        
        while ($stmt->fetch()) {
            $info = array();
            $info['id'] = $id;
            $info['donation_option'] = $donation_option;
          
            array_push($info_list, $info);
        }
 
        return $info_list;
    }

    public function get($info_id)
    {
        $sql = "SELECT id, donation_option FROM " . INFO_TABLE; 
        //condition
        $sql = $sql . " WHERE id = '$info_id' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $donation_option);

        $info = array();
        while ($stmt->fetch()) {
            $info['id'] = $id;
            $info['donation_option'] = $donation_option;
        }
 
        return $info;
    }

    public function insert($donation_option)
    {
        $sql = "INSERT INTO " . INFO_TABLE . "(donation_option) 
        VALUES ('$donation_option')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . INFO_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function getDonationOption()
    {
        $sql = "SELECT donation_option FROM " . INFO_TABLE;
        //condition
        $sql = $sql . " WHERE deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at DESC";
        //constraints
        $sql = $sql . " LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($donation_option);
        
        while ($stmt->fetch()) {
            return $donation_option;
        }
    }
}