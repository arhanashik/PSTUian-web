<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class TeacherDb extends Db
{
    public function __construct()
    {
        parent::__construct(TEACHER_TABLE);
    }

    public function getAll($faculty_id)
    {
        $sql = "SELECT user_id, name, designation, bio, phone, linked_in, address, email, department, blood, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE;
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        return $list = parent::getAll($sql);
    }

    public function getById($user_id)
    {
        $sql = "SELECT name, designation, bio, phone, linked_in, address, email, department, blood, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE; 
        //condition
        $sql = $sql . " WHERE user_id = $user_id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $designation, $bio, $phone, $linked_in, $address, $email, $department, $blood, $faculty_id, $fb_link, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['user_id'] = $user_id;
            $item['name'] = $name;
            $item['designation'] = $designation;
            $item['bio'] = $bio;
            $item['phone'] = $phone;
            $item['linked_in'] = $linked_in;
            $item['address'] = $address;
            $item['email'] = $email;
            $item['department'] = $department;
            $item['blood'] = $blood;
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
        }
 
        return $item;
    }

    public function getByEmail($email)
    {
        $sql = "SELECT user_id, name, designation, bio, phone, linked_in, address, department, blood, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE; 
        //condition
        $sql = $sql . " WHERE email = '$email' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($user_id, $name, $designation, $bio, $phone, $linked_in, $address, $department, $blood, $faculty_id, $fb_link, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['user_id'] = $user_id;
            $item['name'] = $name;
            $item['designation'] = $designation;
            $item['bio'] = $bio;
            $item['phone'] = $phone;
            $item['linked_in'] = $linked_in;
            $item['address'] = $address;
            $item['email'] = $email;
            $item['department'] = $department;
            $item['blood'] = $blood;
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
        }
 
        return empty($item)? false : $item;
    }

    public function isAlreadyInseredByEmail($email)
    {
        $sql = "SELECT user_id FROM $this->table WHERE email = '$email'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function validate($user_id, $email)
    {
        $sql = "SELECT user_id FROM " . TEACHER_TABLE;
        $sql = $sql . " WHERE (user_id = '$user_id' AND email = '$email') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row['user_id'];
        }
    }

    public function insert($user_id, $name, $designation, $department, $email, $faculty_id)
    {
        //columns to select
        $columns = "user_id, name, designation, department, email, faculty_id";
        $sql = "INSERT INTO " . TEACHER_TABLE . "($columns) 
        VALUES ('$user_id', '$name', '$designation','$department', '$email', '$faculty_id')";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_image_url($user_id, $image_url)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set image_url = '$image_url', updated_at = NOW() WHERE user_id = '$user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_name($user_id, $name)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set name = '$name', updated_at = NOW() WHERE user_id = '$user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_bio($user_id, $bio)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set bio = '$bio', updated_at = NOW() WHERE user_id = '$user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_academic_info($user_id, $name, $designation, $department, $blood, $faculty_id)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set name = '$name', designation = '$designation', 
        department = '$department', blood = '$blood', faculty_id = '$faculty_id', 
        updated_at = NOW() WHERE user_id = '$user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_connect_info($user_id, $address, $phone, $email, $linked_in, $fb_link)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set address = '$address', phone = '$phone', 
        email = '$email', linked_in = '$linked_in', fb_link = '$fb_link', updated_at = NOW() 
        WHERE user_id = '$user_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function delete_account($user_id, $email)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set deleted = 1, updated_at = NOW() WHERE user_id = '$user_id' AND email = '$email'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}