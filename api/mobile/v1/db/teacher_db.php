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
        $sql = "SELECT id, name, designation, bio, phone, linked_in, address, email, department, blood, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE;
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        return $list = parent::getAll($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT name, designation, bio, phone, linked_in, address, email, department, blood, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE; 
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $designation, $bio, $phone, $linked_in, $address, $email, $department, $blood, $faculty_id, $fb_link, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['id'] = $id;
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
        $sql = "SELECT id, name, designation, bio, phone, linked_in, address, department, blood, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE; 
        //condition
        $sql = $sql . " WHERE email = '$email' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $name, $designation, $bio, $phone, $linked_in, $address, $department, $blood, $faculty_id, $fb_link, $image_url);

        $item = array();
        while ($stmt->fetch()) {
            $item['id'] = $id;
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

    public function isAlreadyInseredByEmail($email)
    {
        $sql = "SELECT id FROM $this->table WHERE email = '$email'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function validate($email, $password)
    {
        $sql = "SELECT id FROM " . TEACHER_TABLE;
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
        $sql = "SELECT id FROM " . TEACHER_TABLE;
        $sql = $sql . " WHERE (id = '$id' AND password = '$password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
    }

    public function insert($name, $designation, $department, $email, $password, $faculty_id)
    {
        //columns to select
        $columns = "name, designation, department, email, password, faculty_id";
        $sql = "INSERT INTO " . TEACHER_TABLE . "($columns) 
        VALUES ('$name', '$designation','$department', '$email', '$password', '$faculty_id')";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_image_url($id, $image_url)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set image_url = '$image_url', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_name($id, $name)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set name = '$name', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_bio($id, $bio)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set bio = '$bio', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_academic_info($id, $name, $designation, $department, $blood, $faculty_id)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set name = '$name', designation = '$designation', 
        department = '$department', blood = '$blood', faculty_id = '$faculty_id', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_connect_info($id, $address, $phone, $email, $linked_in, $fb_link)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set address = '$address', phone = '$phone', 
        email = '$email', linked_in = '$linked_in', fb_link = '$fb_link', updated_at = NOW() 
        WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function update_password($id, $old_password, $new_password)
    {
        $sql = "UPDATE " . TEACHER_TABLE . " set password = '$new_password', updated_at = NOW() 
        WHERE (id = '$id' AND password = '$old_password') AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}