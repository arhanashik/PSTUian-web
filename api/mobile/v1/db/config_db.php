<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/../constant.php';

class ConfigDb extends Db
{
 
    public function __construct()
    {
        parent::__construct(CONFIG_TABLE);
    }

    public function getLatest() 
    {
        $sql = "SELECT * FROM " . CONFIG_TABLE;
        $sql .= " WHERE deleted = 0 ";
        $sql .= " ORDER BY id DESC LIMIT 1";
        $list = parent::getAll($sql);
        if(!$list || empty($list)) return false;

        return $list[0];
    }
}