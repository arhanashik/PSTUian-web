<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class CheckInDb extends Db
{
    private $columns = "*";
    private $select_query;

   public function __construct()
   {
       parent::__construct(CHECK_IN_TABLE);

       // create select query for the table
       $this->select_query = "SELECT $this->columns FROM " . CHECK_IN_TABLE . " ci ";
   }

   public function getAllByLocation($location_id, $page, $limit)
   {
       $skip_count = $page === 1? 0 : ($page - 1) * $limit;
       $sql = $this->select_query;
       // conditions
       $sql .= " WHERE location_id = '$location_id'";
       // sorting
       $sql .= " ORDER BY created_at DESC";
       // limit and skip
       $sql = $sql . " LIMIT $limit OFFSET $skip_count";

       return parent::getAll($sql);
   }

   public function getAllByUser($user_id, $user_type, $page, $limit)
   {
       $skip_count = $page === 1? 0 : ($page - 1) * $limit;
       $sql = $this->select_query;
       // conditions
       $sql .= " WHERE user_id = '$user_id' AND user_type = '$user_type'";
       // sorting
       $sql .= " ORDER BY ci.updated_at DESC";
       // limit and skip
       $sql = $sql . " LIMIT $limit OFFSET $skip_count";

       return parent::getAll($sql);
   }

   public function getByUser($location_id, $user_id, $user_type)
   {
       $sql = $this->select_query;
       // conditions
       $sql .= " WHERE location_id = '$location_id'";
       $sql .= " AND (user_id = '$user_id' AND user_type = '$user_type')";
       // sorting
       $sql .= " ORDER BY ci.updated_at DESC";
       return parent::getSql($sql);
   }

   public function insert($location_id, $user_id, $user_type)
   {
       $sql = "INSERT INTO " . CHECK_IN_TABLE . "(location_id, user_id, user_type) 
       VALUES ('$location_id', '$user_id', '$user_type')";
       return parent::insertSql($sql);
   }

   public function update($id, $location_id, $user_id, $user_type, $count, $privacy)
   {
       $sql = "UPDATE " . CHECK_IN_TABLE . " SET location_id = '$location_id', 
       user_id = '$user_id', user_type = '$user_type', count = '$count', privacy = '$privacy',
       updated_at = NOW() WHERE id = '$id'";
       return parent::executeSql($sql);
   }

   public function incrementCount($id)
   {
       $sql = "UPDATE " . CHECK_IN_TABLE . " SET count = count + 1, updated_at = NOW() 
       WHERE id = '$id'";
       return parent::executeSql($sql);
   }
}