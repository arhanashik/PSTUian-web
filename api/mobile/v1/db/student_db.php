<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class StudentDb extends Db
{
    public function __construct()
    {
        parent::__construct(STUDENT_TABLE);
    }

    public function getAllByFacultyAndBatch($faculty_id, $batch_id)
    {
        //columns to select
        $columns = "name, id, reg, phone, linked_in, blood, address, email, session, batch_id, faculty_id, fb_link, image_url, cv_link, bio";
        //query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE;
        //condition
        $sql = $sql . " WHERE (faculty_id = $faculty_id AND batch_id = $batch_id) AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY id ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
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

    public function validate($email, $password)
    {

        //columns to select
        // $columns = "name, id, reg, phone, linked_in, blood, address, email, session, batch_id, faculty_id, fb_link, image_url, cv_link, bio";
        $sql = "SELECT id FROM " . STUDENT_TABLE;
        $sql = $sql . " WHERE (email = '$email' AND password = '$password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    }

    public function validateByIdAndPassword($id, $password)
    {

        //columns to select
        // $columns = "name, id, reg, phone, linked_in, blood, address, email, session, batch_id, faculty_id, fb_link, image_url, cv_link, bio";
        $sql = "SELECT id FROM " . STUDENT_TABLE;
        $sql = $sql . " WHERE (id = '$id' AND password = '$password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    }

    public function insert($name, $id, $reg, $email, $batch_id, $session, $faculty_id, $password)
    {
        //columns to select
        $columns = "name, id, reg, email, batch_id, session, faculty_id, password";
        $sql = "INSERT INTO " . STUDENT_TABLE . "($columns) 
        VALUES ('$name', '$id','$reg', '$email', '$batch_id', '$session', '$faculty_id', '$password')";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_image_url($id, $image_url)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set image_url = '$image_url', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_name($id, $name)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set name = '$name', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_bio($id, $bio)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set bio = '$bio', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_academic_info($name, $old_id, $id, $reg, $blood, $faculty_id, $session, $batch_id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set name = '$name', id = '$id', reg = '$reg', blood = '$blood', 
        faculty_id = '$faculty_id', session = '$session', batch_id = '$batch_id', 
        updated_at = NOW() WHERE id = '$old_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_connect_info($id, $address, $phone, $email, $cv_link, $linked_in, $fb_link)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set address = '$address', phone = '$phone', 
        email = '$email', cv_link = '$cv_link', linked_in = '$linked_in', fb_link = '$fb_link', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_cv($id, $cv_link)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set cv_link = '$cv_link', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_password($id, $old_password, $new_password)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set password = '$new_password', updated_at = NOW() 
        WHERE (id = '$id' AND password = '$old_password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function reset_password($id, $password)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set password = '$password', updated_at = NOW() 
        WHERE id = '$id' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}