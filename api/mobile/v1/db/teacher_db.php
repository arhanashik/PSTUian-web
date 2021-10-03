<?php

class TeacherDb
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
        $sql = "SELECT id, name, designation, status, phone, linked_in, address, email, department, fb_link, image_url FROM " . TEACHER_TABLE;
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $name, $designation, $status, $phone, $linked_in, $address, $email, $department, $fb_link, $image_url);
    
        $list = array();
        
        while ($stmt->fetch()) {
            $item = array();
            $item['id'] = $id;
            $item['name'] = $name;
            $item['designation'] = $designation;
            $item['status'] = $status;
            $item['phone'] = $phone;
            $item['linked_in'] = $linked_in;
            $item['address'] = $address;
            $item['email'] = $email;
            $item['department'] = $department;
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
          
            array_push($list, $item);
        }
 
        return $list;
    }

    public function get($id)
    {
        $sql = "SELECT name, designation, status, phone, linked_in, address, email, department, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE; 
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $designation, $status, $phone, $linked_in, $address, $email, $department, $faculty_id, $fb_link, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['id'] = $id;
            $item['name'] = $name;
            $item['designation'] = $designation;
            $item['status'] = $status;
            $item['phone'] = $phone;
            $item['linked_in'] = $linked_in;
            $item['address'] = $address;
            $item['email'] = $email;
            $item['department'] = $department;
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
        }
 
        return $item;
    }

    public function insert($name, $designation, $status, $phone, $linked_in, $address, $email, $department, $faculty_id, $fb_link, $image_url)
    {
        $sql = "INSERT INTO " . TEACHER_TABLE . "(name, designation, status, phone, linked_in, address, email, department, faculty_id, fb_link, image_url) 
        VALUES ('$name', '$designation', '$status', '$phone', '$linked_in', '$address', '$email', '$department', '$faculty_id', '$fb_link', '$image_url')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}