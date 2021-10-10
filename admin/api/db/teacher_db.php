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

    public function getAll()
    {
        $sql = "SELECT t.*, f.short_title FROM " . TEACHER_TABLE . " AS t LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON t.faculty_id = f.id";
        // $sql = "SELECT * FROM " . BATCH_TABLE;
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
        $columns = "t.*, f.short_title AS faculty";
        //query
        $sql = "SELECT $columns FROM " . TEACHER_TABLE . " t 
        LEFT JOIN " . FACULTY_TABLE . " f ON t.faculty_id = f.id";
        //condition
        $sql = $sql . " WHERE t.faculty_id = $faculty_id";
        //sorting
        $sql = $sql . " ORDER BY t.id ASC";
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
        $sql = "SELECT id, short_title, title FROM " . TEACHER_TABLE; 
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

    public function insert($name, $designation, $faculty_id, $department, $address, $phone, $email)
    {
        $sql = "INSERT INTO " . TEACHER_TABLE . "(name, designation, faculty_id, department, address, phone, email) 
        VALUES ('$name', '$designation', '$faculty_id', '$department', '$address', '$phone', '$email')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $name, $designation, $faculty_id, $department, $address, $phone, $email)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set name = '$name', designation = '$designation', 
        faculty_id = '$faculty_id', department = '$department', address = '$address', phone = '$phone', 
        email = '$email', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set deleted = 1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}