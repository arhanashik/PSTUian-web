<?php
require_once './auth_validation.php';
require_once './db/verification_db.php';
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
$db = new VerificationDb();
$logDb = new LogDb();
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
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];

        // check if item exists
        if($db->getByUser($user_id, $user_type)) {
            $response['message'] = 'Already exists!';
            break;
        }
        // insert
        $id = $db->insert($user_id, $user_type);
        if(!$id || $id <= 0) 
        {
            $response['message'] = 'Sorry, failed to create!';
            break;
        }

        $item = $db->getSingle($id);
        $response['success'] = true;
        $response['message'] = 'Checked in successfullly!';
        $response['data'] = $item;
        // add log
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['email_verification']) || strlen($_POST['email_verification']) <= 0) break;

        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $email_verification = $_POST['email_verification'];
        
        $data = $db->update($id, $user_id, $user_type, $email_verification);
        if(!$data || $data <= 0) 
        {
            $response['message'] = 'Update failed!';
            break;
        }

        $item = $db->getSingle($id);
        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $item;
        // add log
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'update', $log_data);
        }
        break;

    case 'confirm':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $result = $db->updateEmailVerification($id, 1);

        $item = $db->getSingle($id);
        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $item;
        
        // add log
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'confirm', $log_data);
        }
        break;

    case 'unconfirm':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $result = $db->updateEmailVerification($id, 0);

        $item = $db->getSingle($id);
        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $item;
        // add log
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'unconfirm', $log_data);
        }
        break;
    
    default:
        break;
}

echo json_encode($response);