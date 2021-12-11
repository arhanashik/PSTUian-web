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

    public function getAll($faculty_id)
    {
        $columns = "b.id, b.name, b.title, b.session, b.total_student, COUNT(s.id)";
        $sql = "SELECT $columns FROM " . BATCH_TABLE . " b LEFT JOIN " . STUDENT_TABLE . " s";
        //condition
        $sql = $sql . " ON s.batch_id = b.id";
        //condition
        $sql = $sql . " WHERE b.faculty_id = $faculty_id AND b.deleted = 0";
        //grouping
        $sql = $sql . " GROUP BY b.id";
        //sorting
        $sql = $sql . " ORDER BY b.session ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $name, $title, $session, $total_student, $registered_student);
    
        $list = array();
        
        while ($stmt->fetch()) {
            $item = array();
            $item['id'] = $id;
            $item['name'] = $name;
            $item['title'] = $title;
            $item['session'] = $session;
            $item['faculty_id'] = $faculty_id;
            $item['total_student'] = $total_student;
            $item['registered_student'] = $registered_student;
          
            array_push($list, $item);
        }
 
        return $list;
    }

    public function get($id)
    {
        $sql = "SELECT name, title, session, faculty_id, total_student FROM " . BATCH_TABLE; 
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $title, $session, $faculty_id, $total_student);

        $batch = array();
        while ($stmt->fetch()) {
            $batch['id'] = $id;
            $batch['name'] = $name;
            $batch['title'] = $title;
            $batch['session'] = $session;
            $batch['faculty_id'] = $faculty_id;
            $batch['total_student'] = $total_student;
        }
 
        return $batch;
    }

    public function insert($name, $title, $session, $faculty_id, $total_student)
    {
        $sql = "INSERT INTO " . BATCH_TABLE . "(name, title, session, faculty_id, total_student) 
        VALUES ('$name', '$title', '$session', '$faculty_id', '$total_student')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($id)
    {
        $sql = "UPDATE " . BATCH_TABLE . " set deleted =  1 WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}