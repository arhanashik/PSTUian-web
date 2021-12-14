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
    // So creating method overloading
    function __call($name_of_function, $arguments) {
        switch ($name_of_function) {
            // for getAll methods
            case 'getAll':
                $sql = "SELECT * FROM $this->table";
                // sorting
                $sql .= " ORDER BY id";
                // condition
                $sql .= " WHERE deleted = 0";
                // if the sql is given as parameter, use that one
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

            default:
                break;
        }
    }

    public function getAllPaged($page, $limit, $sorting_order = 'ASC', $sorting_col = 'id') {
        $skip_count = $page === 1? 0 : ($page - 1) * $limit;
        $sql = "SELECT * FROM $this->table";
        //sorting
        $sql = $sql . " ORDER BY $sorting_col $sorting_order";
        // limit and skip
        $sql = $sql . " LIMIT $limit OFFSET $skip_count";
 
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

    public function getSql($sql)
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows <= 0) return false;
    
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }

    public function insertSql($sql)
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $this->con->insert_id;
    }

    public function executeSql($sql)
    {
        $stmt = $this->con->prepare($sql);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function isAlreadyInsered($id)
    {
        $sql = "SELECT id FROM $this->table WHERE id = '$id'";
        
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