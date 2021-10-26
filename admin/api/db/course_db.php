<?php
require_once dirname(__FILE__) . '/db.php';

class CourseDb extends Db
{
    public function __construct()
    {
        parent::__construct(COURSE_TABLE);
    }

    public function getAll()
    {
        $sql = "SELECT c.*, f.short_title FROM " . COURSE_TABLE . " AS c LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON c.faculty_id = f.id";
        //sorting
        $sql = $sql . " ORDER BY f.short_title";
 
        return parent::getAll($sql);
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
        return parent::getAll($sql);
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
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}