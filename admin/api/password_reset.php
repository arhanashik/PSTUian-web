<?php
require_once './auth_validation.php';
require_once './db/password_reset_db.php';
require_once './db/log_db.php';
require_once './common_request.php';
require_once './util/util.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new PasswordResetDb();
$logDb = new LogDb();
$util = new Util();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($_GET['call']) 
{
    case 'getAll':
        $page = 1;
        $limit = 20;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }
        $response = $db->getAllPaged($page, $limit, "DESC");

        break;
        
    case 'add':
        if(!isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['auth_token'])) {
            break;
        }
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $email = $_POST['email'];
        $auth_token = $_POST['auth_token'];
        if(strlen($auth_token) <= 0 || $auth_token === 'auto') {
            $time_now = date('Y-m-d H:i:s');
            $auth_token = $util->getHash($email.$user_type, $time_now);
        }
        $result = $db->insert($user_id, $user_type, $email, $auth_token);

        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $result;
        // add log
        $log_data = json_encode($db->getSingle($result));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['auth_token'])) {
            break;
        }
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $email = $_POST['email'];
        $auth_token = $_POST['auth_token'];
        if(strlen($auth_token) <= 0 || $auth_token === 'auto') {
            $time_now = date('Y-m-d H:i:s');
            $auth_token = $util->getHash($email.$user_type, $time_now);
        }
        $result = $db->update($id, $user_id, $user_type, $email, $auth_token);
        if(!$result || $result <= 0) {
            $response['message'] = 'Updated failed';
            break;
        }

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        // add log
        $log_data = json_encode($db->getSingle($id));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'update', $log_data);
        }
        break;
    
    default:
        break;
}

echo json_encode($response);