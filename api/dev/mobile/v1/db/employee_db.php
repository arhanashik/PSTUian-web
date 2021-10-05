<?php

class EmplyeeDb
{
    private $con;
 
    public function __construct()
    {
        require_once dirname(__FILE__) . '/db_connect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function getAll($faculty_id)
    {
        $sql = "SELECT id, name, designation, department, phone, address, image_url FROM " . EMPLOYEE_TABLE;
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $name, $designation, $department, $phone, $address, $image_url);
    
        $list = array();
        
        while ($stmt->fetch()) {
            $item = array();
            $item['id'] = $id;
            $item['faculty_id'] = $faculty_id;
            $item['name'] = $name;
            $item['designation'] = $designation;
            $item['department'] = $department;
            $item['phone'] = $phone;
            $item['address'] = $address;
            $item['image_url'] = $image_url;
          
            array_push($list, $item);
        }
 
        return $list;
    }

    public function get($id)
    {
        $sql = "SELECT faculty_id, name, designation, department, phone, address, image_url FROM " . EMPLOYEE_TABLE; 
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($faculty_id, $name, $designation, $department, $phone, $address, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['id'] = $id;
            $item['faculty_id'] = $faculty_id;
            $item['name'] = $name;
            $item['designation'] = $designation;
            $item['department'] = $department;
            $item['phone'] = $phone;
            $item['address'] = $address;
            $item['image_url'] = $image_url;
        }
 
        return $item;
    }

    public function insert($faculty_id, $name, $designation, $department, $phone, $address, $image_url)
    {
        $sql = "INSERT INTO " . EMPLOYEE_TABLE . "(faculty_id, name, designation, department, phone, address, image_url) 
        VALUES ('$faculty_id', '$name', '$designation', '$department', '$phone', '$address', '$image_url')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . EMPLOYEE_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}