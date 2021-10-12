<?php
require_once dirname(__FILE__) . '/db.php';

class StudentDb extends Db
{
    public function __construct()
    {
        parent::__construct(STUDENT_TABLE);
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
        return parent::getAll($sql);
    }

    public function getAllByFaculty($faculty_id)
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
        return parent::getAll($sql);
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
        return parent::getAll($sql);
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
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}