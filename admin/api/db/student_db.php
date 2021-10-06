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

    public function getAll()
    {
        //columns to select
        $columns = "s.*, f.short_title AS faculty, b.name AS batch";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE . " s 
        LEFT JOIN " . FACULTY_TABLE . " f ON s.faculty_id = f.id 
        LEFT JOIN " . BATCH_TABLE . " b ON s.batch_id = b.id";
        //sorting
        $sql = $sql . " ORDER BY id ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
 
        return $list;
    }

    public function getAllByFaculty($faculty_id, $batch_id)
    {
        //columns to select
        $columns = "s.*, f.short_title AS faculty, b.name AS batch";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE . " s 
        LEFT JOIN " . FACULTY_TABLE . " f ON s.faculty_id = f.id 
        LEFT JOIN " . BATCH_TABLE . " b ON s.batch_id = b.id";
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id";
        //sorting
        $sql = $sql . " ORDER BY id ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
 
        return $list;
    }

    public function getAllByFacultyAndBatch($faculty_id, $batch_id)
    {
        //columns to select
        $columns = "s.*, f.short_title AS faculty, b.name AS batch";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE . " s 
        LEFT JOIN " . FACULTY_TABLE . " f ON s.faculty_id = f.id 
        LEFT JOIN " . BATCH_TABLE . " b ON s.batch_id = b.id";
        //condition
        $sql = $sql . " WHERE s.faculty_id = $faculty_id AND s.batch_id = $batch_id";
        //sorting
        $sql = $sql . " ORDER BY s.id ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
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

    public function update($name, $id, $reg, $batch_id, $session, $faculty_id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set name = '$name', reg = '$reg', 
        batch_id = '$batch_id', session = '$session', faculty_id = '$faculty_id', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set deleted =  1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}