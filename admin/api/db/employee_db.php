<?php
require_once dirname(__FILE__) . '/db.php';

class EmployeeDb extends Db
{
    public function __construct()
    {
        parent::__construct(EMPLOYEE_TABLE);
    }

    public function getAll()
    {
        $sql = "SELECT e.*, f.short_title FROM " . EMPLOYEE_TABLE . " AS e LEFT JOIN " . FACULTY_TABLE . " AS f 
        ON e.faculty_id = f.id";
        //sorting
        $sql = $sql . " ORDER BY f.short_title";
        return parent::getAll($sql);
    }

    public function getAllByFaculty($faculty_id)
    {
        //columns to select
        $columns = "e.*, f.short_title AS faculty";
        //query
        $sql = "SELECT $columns FROM " . EMPLOYEE_TABLE . " e 
        LEFT JOIN " . FACULTY_TABLE . " f ON e.faculty_id = f.id";
        //condition
        $sql = $sql . " WHERE e.faculty_id = $faculty_id";
        //sorting
        $sql = $sql . " ORDER BY e.id ASC";
        return parent::getAll($sql);
    }

    public function insert($name, $designation, $faculty_id, $department, $address, $phone)
    {
        $sql = "INSERT INTO " . EMPLOYEE_TABLE . "(name, designation, faculty_id, department, address, phone) 
        VALUES ('$name', '$designation', '$faculty_id', '$department', '$address', '$phone')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $name, $designation, $faculty_id, $department, $address, $phone)
    {
        $sql = "UPDATE " . EMPLOYEE_TABLE . " set name = '$name', designation = '$designation', 
        faculty_id = '$faculty_id', department = '$department', address = '$address', 
        phone = '$phone', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}