<?php

class CourseDb
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
        //columns to select
        $columns = "id, course_code, course_title, credit_hour, status";
        //query
        $sql = "SELECT $columns FROM " . COURSE_TABLE;
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $course_code, $course_title, $credit_hour, $status);
    
        $list = array();
        
        while ($stmt->fetch()) {
            $item = array();
            $item['id'] = $id;
            $item['course_code'] = $course_code;
            $item['course_title'] = $course_title;
            $item['credit_hour'] = $credit_hour;
            $item['faculty_id'] = $faculty_id;
            $item['status'] = $status;
          
            array_push($list, $item);
        }
 
        return $list;
    }

    public function get($id)
    {
        //columns to select
        $columns = "course_code, course_title, credit_hour, faculty_id, status";
        //query
        $sql = "SELECT $columns FROM " . COURSE_TABLE;
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $title, $session, $faculty_id, $total_student);

        $item = array();
        while ($stmt->fetch()) {
            $item['id'] = $id;
            $item['course_code'] = $course_code;
            $item['course_title'] = $course_title;
            $item['credit_hour'] = $icredit_hourd;
            $item['faculty_id'] = $faculty_id;
            $item['status'] = $status;
        }
 
        return $item;
    }

    public function insert($name, $title, $session, $faculty_id, $total_student)
    {
        $sql = "INSERT INTO " . COURSE_TABLE . "(id, course_code, course_title, credit_hour, faculty_id, status) 
        VALUES ('$name', '$title', '$session', '$faculty_id', '$total_student')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . COURSE_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}