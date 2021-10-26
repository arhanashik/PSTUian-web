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
        $sql = "SELECT id, name, designation, bio, phone, linked_in, address, email, department, fb_link, image_url FROM " . TEACHER_TABLE;
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
        $sql = "SELECT name, designation, bio, phone, linked_in, address, email, department, faculty_id, fb_link, image_url FROM " . TEACHER_TABLE; 
        //condition
        $sql = $sql . " WHERE id = $id AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($name, $designation, $bio, $phone, $linked_in, $address, $email, $department, $faculty_id, $fb_link, $image_url);

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
            $item['faculty_id'] = $faculty_id;
            $item['fb_link'] = $fb_link;
            $item['image_url'] = $image_url;
        }
 
        return $item;
    }
}