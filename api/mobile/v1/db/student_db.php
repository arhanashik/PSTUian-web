<?php

class StudentDb
{
    private $con;
 
    public function __construct()
    {
        require_once dirname(__FILE__) . '/db_connect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function getAll($faculty_id, $batch_id)
    {
        //columns to select
        $columns = "name, id, reg, phone, linked_in, blood, address, email, session, fb_link, image_url";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE;
        //condition
        $sql = $sql . " WHERE (faculty_id = $faculty_id AND batch_id = $batch_id) AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY id ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $id, $reg, $phone, $linked_in, $blood, $address, $email, $session, $fb_link, $image_url);
    
        $list = array();
        
        while ($stmt->fetch()) {
            $item = array();
            $item['name'] = $name;
            $item['id'] = $id;
            $item['reg'] = $reg;
            $item['phone'] = $phone;
            $item['linked_in'] = $linked_in;
            $item['blood'] = $blood;
            $item['address'] = $address;
            $item['email'] = $email;
            $item['batch_id'] = $batch_id;
            $item['session'] = $session;
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
          
            array_push($list, $item);
        }
 
        return $list;
    }

    public function get($id)
    {
        //columns to select
        $columns = "name, reg, phone, linked_in, blood, address, email, batch_id, session, faculty_id, fb_link, image_url";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE;
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $reg, $phone, $linked_in, $blood, $address, $email, $batch_id, $session, $faculty_id, $fb_link, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['name'] = $name;
            $item['id'] = $id;
            $item['reg'] = $reg;
            $item['phone'] = $phone;
            $item['linked_in'] = $linked_in;
            $item['blood'] = $blood;
            $item['address'] = $address;
            $item['email'] = $email;
            $item['batch_id'] = $batch_id;
            $item['session'] = $session;
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
        }
 
        return $item;
    }

    public function insert($name, $id, $reg, $phone, $linked_in, $blood, $address, $email, $batch_id, $session, $faculty_id, $fb_link, $image_url)
    {
        //columns to select
        $columns = "name, id, reg, phone, linked_in, blood, address, email, batch_id, session, faculty_id, fb_link, image_url";
        $sql = "INSERT INTO " . STUDENT_TABLE . "($columns) 
        VALUES ('$name', '$id','$reg', '$phone', '$linked_in', '$blood', '$address', '$email', '$batch_id', '$session', '$faculty_id', '$fb_link', '$image_url')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}