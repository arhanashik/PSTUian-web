<?php
require_once dirname(__FILE__) . '/db.php';

class TeacherDb extends Db
{
    public function __construct()
    {
        parent::__construct(TEACHER_TABLE);
    }

    public function getAll()
    {
        $sql = "SELECT t.*, f.short_title FROM " . TEACHER_TABLE . " AS t LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON t.faculty_id = f.id";
        //sorting
        $sql = $sql . " ORDER BY f.short_title";
        return parent::getAll($sql);
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
        return parent::getAll($sql);
    }

    public function insert($name, $designation, $faculty_id, $department, $address, $phone, $email, $password)
    {
        $sql = "INSERT INTO " . TEACHER_TABLE . "(name, designation, faculty_id, department, address, phone, email, password) 
        VALUES ('$name', '$designation', '$faculty_id', '$department', '$address', '$phone', '$email', '$password')";
        
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
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}