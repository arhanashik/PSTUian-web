<?php
session_start();
require_once './constant.php';
require_once './db/auth_db.php';
require_once './db/admin_db.php';
require_once './db/log_db.php';
require_once './util/util.php';
 
$response = array();
$response['success'] = false;
$response['code'] = ERROR_REQUIRED_PARAMETES_MISSING;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new AuthDb();
$adminDb = new AdminDb();
$logDb = new LogDb();
$util = new Util();
 
switch ($_GET['call']) {
    case 'getAll':
        //get the auth token from the request header
        if(isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
            $auth_token = $_SERVER['HTTP_X_AUTH_TOKEN'];
            $validate = $db->isValidToken($auth_token);
            if($validate) {
                $response = $db->getAll();
            }
        }
        break;

    case 'signIn':
        if (isset($_POST['email']) && strlen($_POST['email']) > 0 
            && isset($_POST['password']) && strlen($_POST['password']) > 0
            && isset($_POST['user_type']) && strlen($_POST['user_type']) > 0)
        {
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $user_type = $_POST['user_type'];

            if($user_type === 'admin') {
                $user = $adminDb->validate($email, $password);
            } else {
                $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
                $response['message'] = 'Invaild Account!';
                break;
            }
            
            if(!$user) {
                $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
                $response['message'] = 'Invaild Account!';
            } else {
                $time_now = date('Y-m-d H:i:s');
                $auth_token = $util->getHash($email.$user_type, $time_now);
                $old_auth = $db->getByUserIdAndType($user['id'], $user_type);
                if(empty($old_auth)) {
                    $result = $db->insert($user['id'], $user_type, $auth_token);
                } else {
                    $result = $db->update($user['id'], $user_type, $auth_token);
                }
                if($result) {
                    $_SESSION[$user_type] = $user;
                    $_SESSION['auth_token'] = $auth_token;
                    $response['success'] = true;
                    $response['code'] = SUCCESS;
                    $response['message'] = 'Signed in Successfullly';
                    $response[$user_type] = $user;
                    $response['auth_token'] = $auth_token;
                } else {
                    $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
                    $response['message'] = 'Failed to authenticate!';
                }
            }
        }

        break;

    case 'signOut':
        if (isset($_GET['user_type']) && strlen($_GET['user_type']) > 0)
        {
            $user_type = $_GET['user_type'];
            $user = $_SESSION[$user_type];
            $invalidateAuth = $db->invalidateAuth($user['id'], $user_type);
            if($invalidateAuth) {
                session_destroy(); 
                $response['success'] = true;
                $response['code'] = SUCCESS;
                $response['message'] = 'Signed out successfullly';
            } else {
                $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
                $response['message'] = 'Failed to signed out!';
            }
        }

        break;

    case 'delete':
        if(!isset($_POST['id']) || empty($_POST['id'])) break;
        $id = $_POST['id'];
        $log_data = json_encode($db->getSingle($id));
        $result = $db->delete($id);
        $response['success'] = true;
        $response['message'] = 'Deleted Successfully';
        $response['data'] = $result;
        // add log
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'delete', $log_data);
        }
        break;

    case 'restore':
        if(!isset($_POST['id']) || empty($_POST['id'])) break;
        $id = $_POST['id'];
        $result = $db->restore($id);
        $response['success'] = true;
        $response['message'] = 'Restored Successfully';
        $response['data'] = $result;
        // add log
        $log_data = json_encode($db->getSingle($id));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'restore', $log_data);
        }
        break;

    case 'deletePermanent':
        if(!isset($_POST['id']) || empty($_POST['id'])) break;
        $id = $_POST['id'];
        $log_data = json_encode($db->getSingle($id));
        $result = $db->deletePermanent($id);
        $response['success'] = true;
        $response['message'] = 'Deleted Successfully';
        $response['data'] = $result;
        // add log
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'delete_permanent', $log_data);
        }
        break;
}
 
echo json_encode($response);