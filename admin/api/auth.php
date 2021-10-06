<?php
session_start();
require_once './constant.php';
require_once './db/auth_db.php';
 
$response = array();
$response['success'] = false;
$response['code'] = ERROR_REQUIRED_PARAMETES_MISSING;
$response['message'] = 'Required parameters are missing';
 
if (isset($_GET['call'])) {
    switch ($_GET['call']) {
        case 'signIn':
            if (isset($_POST['email']) && strlen($_POST['email']) > 0 
                && isset($_POST['password']) && strlen($_POST['password']) > 0)
            {
                $db = new AuthDb();

                $email = $_POST['email'];
                $password = $_POST['password'];

                $user = $db->validate($email, $password);
                if(!$user) {
                    $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
                    $response['message'] = 'Invaild Account!';
                } else {
                    $_SESSION['admin'] = $user;
                    $response['success'] = true;
                    $response['code'] = SUCCESS;
                    $response['message'] = 'Signed in Successfullly';
                    $response['admin'] = $_SESSION['admin'];
                }
            }
    
            break;

        case 'signOut':
            session_destroy(); 
            $response['success'] = true;
            $response['code'] = SUCCESS;
            $response['message'] = 'Signed out Successfullly';
            header('Location: ../login.php');
    
            break;
    }
}
 
echo json_encode($response);