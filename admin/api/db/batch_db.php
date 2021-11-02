<?php
require_once dirname(__FILE__) . '/db.php';

class BatchDb extends Db
{
    public function __construct()
    {
        parent::__construct(BATCH_TABLE);
    }

    public function getAll()
    {
        $sql = "SELECT b.*, f.short_title FROM " . BATCH_TABLE . " AS b LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON b.faculty_id = f.id";
        //sorting
        $sql = $sql . " ORDER BY f.short_title";
 
        return parent::getAll($sql);
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
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}