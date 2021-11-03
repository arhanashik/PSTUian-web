<?php
require_once './auth_validation.php';
require_once './db/admin_db.php';
require_once './db/log_db.php';
require_once './common_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new AdminDb();
$logDb = new LogDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($_GET['call']) 
{
    case 'add':
        if(!isset($_POST['email']) || empty($_POST['email'])
        || !isset($_POST['password']) || empty($_POST['password'])
        || !isset($_POST['role']) || empty($_POST['role'])) {
            break;
        }
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        if(strlen($password) < 6) {
            $response['message'] = 'Minmun password length is 6';
            break;
        }
        $result = $db->insert($email, md5($password), $role);
        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $result;

        // add log
        $data = array();
        $data['email'] = $email;
        $data['role'] = $role;
        $log_data = json_encode($data);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if(!isset($_POST['id']) || empty($_POST['id'])
        || !isset($_POST['email']) || empty($_POST['email'])
        || !isset($_POST['password']) || empty($_POST['password'])
        || !isset($_POST['role']) || empty($_POST['role'])) {
            break;
        }
        $id = $_POST['id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        if(strlen($password) < 6) {
            $response['message'] = 'Minmun password length is 6';
            break;
        }
        $result = $db->update($id, $email, md5($password), $role);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;

        // add log
        $data = array();
        $data['id'] = $id;
        $data['email'] = $email;
        $data['role'] = $role;
        $log_data = json_encode($data);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'update', $log_data);
        }
        break;

    case 'changePassword':
        if(!isset($_POST['id']) || empty($_POST['id'])
        || !isset($_POST['old_password']) || empty($_POST['old_password'])
        || !isset($_POST['new_password']) || empty($_POST['new_password'])) {
            break;
        }
        $id = $_POST['id'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        if(strlen($old_password) < 6 || strlen($new_password) < 6) {
            $response['message'] = 'Minmun password length is 6';
            break;
        }
        $result = $db->updatePassword($id, md5($old_password), md5($new_password));

        if(!$result) {
            $response['message'] = 'Failed! Please check if old password is correct.';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        break;
    
    default:
        break;
}

echo json_encode($response);