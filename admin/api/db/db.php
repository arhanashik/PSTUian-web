<?php

class Db {
    public $con;
    public $table;
 
    public function __construct($table)
    {
        require_once dirname(__FILE__) . '/db_connect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();

        $this->table = $table;
    }

    // __call accepts function name and arguments
    // So creating method overloading for getAll methods
    function __call($name_of_function, $arguments) {
        // It will match the function name
        if($name_of_function != 'getAll') {
            return;
        }
         
        $sql = "SELECT * FROM $this->table";
        //sorting
        $sql = $sql . " ORDER BY id";
        if (count($arguments) > 0) {
            $sql = $arguments[0];
        }
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $list = array();
        while ($row = $result->fetch_assoc()) {
            array_push($list, $row);
        }
 
        return $list;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM $this->table";
        //condition
        $sql = $sql . " WHERE id = '$id' AND deleted = 0";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }

    public function isAlreadyInsered($id)
    {
        $sql = "SELECT id FROM $table WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows > 0;
    }

    public function delete($id)
    {
        $sql = "UPDATE $this->table set deleted = 1, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function deletePermanent($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function restore($id)
    {
        $sql = "UPDATE $this->table set deleted = 0, updated_at = NOW() WHERE id = '$id'";
        
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
}