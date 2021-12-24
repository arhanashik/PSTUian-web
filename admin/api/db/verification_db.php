<?php
require_once dirname(__FILE__) . '/db.php';

class VerificationDb extends Db
{
    private $columns = "*";
    private $select_query;

    public function __construct()
    {
        parent::__construct(VERIFICATION_TABLE);

        // create select query for the table
        $this->select_query = "SELECT $this->columns FROM " . VERIFICATION_TABLE;
    }

    public function getByUser($user_id, $user_type)
    {
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE user_id = '$user_id' AND user_type = '$user_type'";
        // sorting
        $sql .= " ORDER BY id DESC";
        return parent::getSql($sql);
    }

    public function getById($id)
    {
        $sql = $this->select_query;
        // conditions
        $sql .= " WHERE id = '$id'";
        return parent::getSql($sql);
    }

    public function insert($user_id, $user_type)
    {
        $sql = "INSERT INTO " . VERIFICATION_TABLE . "(user_id, user_type) 
        VALUES ('$user_id', '$user_type')";
        return parent::insertSql($sql);
    }

    public function update($id, $user_id, $user_type, $email_verification)
    {
        $sql = "UPDATE " . VERIFICATION_TABLE . " SET user_id = '$user_id', 
        user_type = '$user_type', email_verification = '$email_verification', 
        updated_at = NOW() WHERE id = '$id'";
        return parent::executeSql($sql);
    }

    public function updateEmailVerification($id, $email_verification)
    {
        $sql = "UPDATE $this->table set email_verification = '$email_verification', 
        updated_at = NOW() WHERE id = '$id'";
        return parent::executeSql($sql);
    }
}