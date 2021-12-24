<?php
require_once './auth_validation.php';
require_once './db/donation_db.php';
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
$db = new DonationDb();
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
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['info']) || strlen($_POST['info']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['reference']) || strlen($_POST['reference']) <= 0) {
            break;
        }
        $name = $_POST['name'];
        $info = $_POST['info'];
        $email = $_POST['email'];
        $reference = $_POST['reference'];
        $result = $db->insert($name, $info, $email, $reference);
        if(!$result || $result <= 0) {
            $response['message'] = 'Insertion failed';
            break;
        }

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
        || !isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['info']) || strlen($_POST['info']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['reference']) || strlen($_POST['reference']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $name = $_POST['name'];
        $info = $_POST['info'];
        $email = $_POST['email'];
        $reference = $_POST['reference'];
        $result = $db->update($id, $name, $info, $email, $reference);
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

    case 'confirm':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $result = $db->updateConfirmation($id, 1);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        // add log
        $log_data = json_encode($db->getSingle($id));
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
        $result = $db->updateConfirmation($id, 0);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        // add log
        $log_data = json_encode($db->getSingle($id));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'unconfirm', $log_data);
        }
        break;
    
    default:
        break;
}

echo json_encode($response);