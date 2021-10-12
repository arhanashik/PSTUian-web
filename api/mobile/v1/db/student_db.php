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
        $columns = "name, id, reg, phone, linked_in, blood, address, email, session, fb_link, image_url, cv_link";
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
        $stmt->bind_result($name, $id, $reg, $phone, $linked_in, $blood, $address, $email, $session, $fb_link, $image_url, $cv_link);
    
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
            $item['cv_link'] = $cv_link;
          
            array_push($list, $item);
        }
 
        return $list;
    }

    public function get($id)
    {
        //columns to select
        $columns = "name, reg, phone, linked_in, blood, address, email, batch_id, session, faculty_id, fb_link, image_url, cv_link";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE;
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $reg, $phone, $linked_in, $blood, $address, $email, $batch_id, $session, $faculty_id, $fb_link, $image_url, $cv_link);

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
            $item['cv_link'] = $cv_link;
        }
 
        return $item;
    }

    public function isAlreadyInsered($id)
    {
        $sql = "SELECT id FROM " . STUDENT_TABLE . " WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function insert($name, $id, $reg, $batch_id, $session, $faculty_id)
    {
        //columns to select
        $columns = "name, id, reg, batch_id, session, faculty_id";
        $sql = "INSERT INTO " . STUDENT_TABLE . "($columns) 
        VALUES ('$name', '$id','$reg', '$batch_id', '$session', '$faculty_id')";
        
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