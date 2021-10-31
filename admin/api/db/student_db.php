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
        $columns = "s.name, s.id, s.reg, s.phone, s.linked_in, s.blood, s.address, s.email, 
        s.batch_id, s.session, s.faculty_id, s.fb_link, s.image_url, s.cv_link, s.deleted, 
        s.created_at, s.updated_at, f.short_title AS faculty, b.name AS batch";
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
        $columns = "s.name, s.id, s.reg, s.phone, s.linked_in, s.blood, s.address, s.email, 
        s.batch_id, s.session, s.faculty_id, s.fb_link, s.image_url, s.cv_link, s.deleted, 
        s.created_at, s.updated_at, f.short_title AS faculty, b.name AS batch";
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
        $columns = "s.name, s.id, s.reg, s.phone, s.linked_in, s.blood, s.address, s.email, 
        s.batch_id, s.session, s.faculty_id, s.fb_link, s.image_url, s.cv_link, s.deleted, 
        s.created_at, s.updated_at, f.short_title AS faculty, b.name AS batch";
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

    public function getByEmail($email)
    {
        $sql = "SELECT * FROM " . STUDENT_TABLE;
        //condition
        $sql = $sql . " WHERE email = '$email' AND deleted = 0";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }

    public function insert($name, $id, $reg, $email, $batch_id, $session, $faculty_id, $password)
    {
        //columns to select
        $columns = "name, id, reg, email, batch_id, session, faculty_id, password";
        $sql = "INSERT INTO " . STUDENT_TABLE . "($columns) 
        VALUES ('$name', '$id','$reg', '$email', '$batch_id', '$session', '$faculty_id', '$password')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($name, $old_id, $id, $reg, $email, $batch_id, $session, $faculty_id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set id='$id', name = '$name', reg = '$reg', 
        email = '$email', batch_id = '$batch_id', session = '$session', 
        faculty_id = '$faculty_id', updated_at = NOW() WHERE id = '$old_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}