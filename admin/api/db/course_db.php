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

    public function getAll()
    {
        $sql = "SELECT c.*, f.short_title FROM " . COURSE_TABLE . " AS c LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON c.faculty_id = f.id";
        //sorting
        $sql = $sql . " ORDER BY f.short_title";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
 
        return $list;
    }

    public function getAllByFaculty($faculty_id)
    {
        //columns to select
        $columns = "c.*, f.short_title AS faculty";
        //query
        $sql = "SELECT $columns FROM " . COURSE_TABLE . " c 
        LEFT JOIN " . FACULTY_TABLE . " f ON c.faculty_id = f.id";
        //condition
        $sql = $sql . " WHERE c.faculty_id = $faculty_id";
        //sorting
        $sql = $sql . " ORDER BY c.id ASC";
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
        $sql = "SELECT id, short_title, title FROM " . COURSE_TABLE; 
        //condition
        $sql = $sql . " WHERE id = '$id' AND deleted = 0";
        
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

    public function isAlreadyInsered($course_code)
    {
        $sql = "SELECT id FROM " . COURSE_TABLE . " WHERE course_code = '$course_code'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function insert($course_code, $course_title, $credit_hour, $faculty_id)
    {
        $sql = "INSERT INTO " . COURSE_TABLE . "(course_code, course_title, credit_hour, faculty_id) 
        VALUES ('$course_code', '$course_title', '$credit_hour', '$faculty_id')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $course_code, $course_title, $credit_hour, $faculty_id)
    {
        $sql = "UPDATE " . COURSE_TABLE . " set course_code = '$course_code', course_title = '$course_title', 
        credit_hour = '$credit_hour', faculty_id = '$faculty_id', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "UPDATE " . COURSE_TABLE . " set deleted = 1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE " . COURSE_TABLE . " set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}