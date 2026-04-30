<?php
require_once './db/auth_db.php';
require_once './constant.php';

// This file checkes for auth token[@param x-auth-token] in request header.
// And then it tries to check if the token is available in the database.
// If it is not, it returns a error response and stop furture execution of code.
$validate = false;
if(isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
    $authDb = new AuthDb();
    $auth_token = $_SERVER['HTTP_X_AUTH_TOKEN'];
    $validate = $authDb->isValidToken($auth_token);
}

if(!$validate) {
    $response = array();
    $response['code'] = AUTH_FAILED;
    $response['message'] = 'Invalid request';
    echo json_encode($response);
    exit();
}
