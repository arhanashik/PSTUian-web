<?php
require_once dirname(__FILE__) . '/db.php';

class SliderDb extends Db
{
 
    public function __construct()
    {
        parent::__construct(SLIDER_TABLE);
    }

    public function insert($title, $image_url)
    {
        $sql = "INSERT INTO " . SLIDER_TABLE . "(title, image_url) 
        VALUES ('$title', '$image_url')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function update($id, $title, $image_url)
    {
        $sql = "UPDATE " . SLIDER_TABLE . " set title = '$title', image_url = '$image_url', 
        updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}