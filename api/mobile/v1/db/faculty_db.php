<?php

class FacultyDb
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
        $sql = "SELECT id, short_title, title FROM " . FACULTY_TABLE;
        //condition
        $sql = $sql . " WHERE deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $short_title, $title);
    
        $faculties = array();
        
        while ($stmt->fetch()) {
            $faculty = array();
            $faculty['id'] = $id;
            $faculty['short_title'] = $short_title;
            $faculty['title'] = $title;
          
            array_push($faculties, $faculty);
        }
 
        return $faculties;
    }

    public function get($faculty_id)
    {
        $sql = "SELECT id, short_title, title FROM " . FACULTY_TABLE; 
        //condition
        $sql = $sql . " WHERE id = '$faculty_id' AND deleted = 0";
        
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

    public function insert($short_title, $title)
    {
        $sql = "INSERT INTO " . FACULTY_TABLE . "(short_title, title) 
        VALUES ('$short_title', '$title')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . FACULTY_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}