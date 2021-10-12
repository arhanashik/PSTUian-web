<?php
require_once dirname(__FILE__) . '/db.php';

class FacultyDb extends Db
{
 
    public function __construct()
    {
        parent::__construct(FACULTY_TABLE);
    }

    public function insert($short_title, $title)
    {
        $sql = "INSERT INTO " . FACULTY_TABLE . "(short_title, title) 
        VALUES ('$short_title', '$title')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $short_title, $title)
    {
        $sql = "UPDATE " . FACULTY_TABLE . " set short_title = '$short_title', title = '$title', updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}