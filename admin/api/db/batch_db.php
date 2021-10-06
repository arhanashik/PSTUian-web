<?php

class BatchDb
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
        $sql = "SELECT b.*, f.short_title FROM " . BATCH_TABLE . " AS b LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON b.faculty_id = f.id";
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
        $sql = "SELECT * FROM " . BATCH_TABLE; 
        //condition
        $sql = $sql . " WHERE faculty_id = '$faculty_id'";
        //sorting
        $sql = $sql . " ORDER BY session";
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
        $sql = "SELECT id, short_title, title FROM " . BATCH_TABLE; 
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

    public function insert($name, $title, $session, $faculty_id, $total_student)
    {
        $sql = "INSERT INTO " . BATCH_TABLE . "(name, title, session, faculty_id, total_student) 
        VALUES ('$name', '$title', '$session', '$faculty_id', '$total_student')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $name, $title, $session, $faculty_id, $total_student)
    {
        $sql = "UPDATE " . BATCH_TABLE . " set name = '$name', title = '$title', session = '$session', faculty_id = '$faculty_id', total_student = '$total_student', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "UPDATE " . BATCH_TABLE . " set deleted = 1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }

    public function restore($id)
    {
        $sql = "UPDATE " . BATCH_TABLE . " set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}