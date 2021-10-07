<?php
require_once './db/auth_db.php';

$validate = false;
if(isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
    $db = new AuthDb();
    $auth_token = $_SERVER['HTTP_X_AUTH_TOKEN'];
    $validate = $db->isValidToken($auth_token);
}
