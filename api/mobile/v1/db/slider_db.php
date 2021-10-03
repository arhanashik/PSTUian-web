<?php

class SliderDb
{
    private $con;
 
    public function __construct()
    {
        require_once dirname(__FILE__) . '/db_connect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function getAll()
    {
        $sql = "SELECT id, title, image_url FROM " . SLIDER_TABLE;
        //condition
        $sql = $sql . " WHERE deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $title, $image_url);
    
        $sliders = array();
        
        while ($stmt->fetch()) {
            $slider = array();
            $slider['id'] = $id;
            $slider['title'] = $title;
            $slider['image_url'] = $image_url;
          
            array_push($sliders, $slider);
        }
 
        return $sliders;
    }

    public function get($slider_id)
    {
        $sql = "SELECT id, title, image_url FROM " . SLIDER_TABLE; 
        //condition
        $sql = $sql . " WHERE id = '$slider_id' AND deleted = 0";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $title, $image_url);

        $slider = array();
        while ($stmt->fetch()) {
            $slider['id'] = $id;
            $slider['title'] = $title;
            $slider['image_url'] = $image_url;
        }
 
        return $slider;
    }

    public function insert($title, $image_url)
    {
        $sql = "INSERT INTO " . SLIDER_TABLE . "(title, image_url) 
        VALUES ('$title', '$image_url')";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function delete($slider_id)
    {
        $sql = "UPDATE " . SLIDER_TABLE . " set deleted =  1 WHERE id = '$slider_id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute();
    }
}