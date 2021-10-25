<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class EmplyeeDb extends Db
{
    public function __construct()
    {
        parent::__construct(EMPLOYEE_TABLE);
    }

    public function getAll($faculty_id)
    {
        $sql = "SELECT id, name, designation, department, phone, address, image_url FROM " . EMPLOYEE_TABLE;
        //condition
        $sql = $sql . " WHERE faculty_id = $faculty_id AND deleted = 0";
        //sorting
        $sql = $sql . " ORDER BY created_at ASC";
        //constraints
        // $sql = $sql . " LIMIT $limit OFFSET $skip_item_count";
        return $list = parent::getAll($sql);
    }
}