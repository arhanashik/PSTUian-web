<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class StudentDb extends Db
{
    public function __construct()
    {
        parent::__construct(STUDENT_TABLE);
    }

    public function getAllByFacultyAndBatch($faculty_id, $batch_id, $page, $limit)
    {
        // Calculate offset
        $offset = ($page - 1) * $limit;

        // Columns to select
        $columns = "auth_user_id, name, id, reg, phone, linked_in, blood, address, email, session, batch_id, faculty_id, fb_link, image_url, cv_link, bio";

        // Query
        $sql = "SELECT $columns FROM " . STUDENT_TABLE;

        // Conditions
        $sql .= " WHERE faculty_id = $faculty_id
                AND batch_id = $batch_id
                AND deleted = 0";

        // Sorting
        $sql .= " ORDER BY id ASC";

        // Pagination
        $sql .= " LIMIT $limit OFFSET $offset";

        return parent::getAll($sql);
    }

    public function isAlreadyInseredByEmail($email)
    {
        $sql = "SELECT auth_user_id FROM $this->table WHERE email = '$email'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function getByAuthUserId($auth_user_id)
    {
        $sql = "SELECT * FROM " . STUDENT_TABLE . " WHERE auth_user_id = '$auth_user_id' AND deleted = 0";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            unset($row['password']);
            return $row;
        }
    }

    public function getByEmail($email)
    {
        $sql = "SELECT * FROM " . STUDENT_TABLE . " WHERE email = '$email' AND deleted = 0";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            unset($row['password']);
            return $row;
        }
    }

    public function validate($email, $password)
    {

        $sql = "SELECT auth_user_id FROM " . STUDENT_TABLE;
        $sql = $sql . " WHERE (email = '$email' AND password = '$password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row['auth_user_id'];
        }
    }

    public function insert(
        $name,
        $id,
        $reg,
        $faculty_id,
        $batch_id,
        $session,
        $email,
        $password
    ) {
        $sql = "INSERT INTO " . STUDENT_TABLE . "
                (`name`, `id`, `reg`, `faculty_id`, `batch_id`, `session`, `email`, `password`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param(
            "ssssssss",
            $name,
            $id,
            $reg,
            $faculty_id,
            $batch_id,
            $session,
            $email,
            $password
        );

        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_auth_user_id($auth_user_id, $email, $password)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set auth_user_id = '$auth_user_id', updated_at = NOW()";
        //condition
        $sql = $sql . " WHERE email = '$email' AND password = '$password'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_image_url($auth_user_id, $image_url)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set image_url = '$image_url', updated_at = NOW() WHERE auth_user_id = '$auth_user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_name($auth_user_id, $name)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set name = '$name', updated_at = NOW() WHERE auth_user_id = '$auth_user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_bio($auth_user_id, $bio)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set bio = '$bio', updated_at = NOW() WHERE auth_user_id = '$auth_user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_academic_info($auth_user_id, $name, $id, $reg, $blood, $faculty_id, $session, $batch_id)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set name = '$name', id = '$id', reg = '$reg', blood = '$blood', 
        faculty_id = '$faculty_id', session = '$session', batch_id = '$batch_id', 
        updated_at = NOW() WHERE auth_user_id = '$auth_user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_connect_info($auth_user_id, $address, $phone, $email, $cv_link, $linked_in, $fb_link)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set address = '$address', phone = '$phone', 
        email = '$email', cv_link = '$cv_link', linked_in = '$linked_in', fb_link = '$fb_link', 
        updated_at = NOW() WHERE auth_user_id = '$auth_user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_cv($auth_user_id, $cv_link)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set cv_link = '$cv_link', updated_at = NOW() WHERE auth_user_id = '$auth_user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_password($email, $old_password, $new_password)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set password = '$new_password', updated_at = NOW() 
        WHERE (email = '$email' AND password = '$old_password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function delete_account($email, $password)
    {
        $sql = "UPDATE " . STUDENT_TABLE . " set deleted = 1, updated_at = NOW() WHERE email = '$email' AND password = '$password'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}