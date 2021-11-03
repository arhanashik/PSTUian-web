<?php
require_once './auth_validation.php';
require_once './db/info_db.php';
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
$db = new InfoDb();
$logDb = new LogDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'add':
        if(!isset($_POST['donation_option']) || strlen($_POST['donation_option']) <= 0) {
            break;
        }
        $donation_option = $_POST['donation_option'];
        $result = $db->insert($donation_option);
        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        // add log
        $log_data = json_encode($donation_option);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0 
        || !isset($_POST['donation_option']) || strlen($_POST['donation_option']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $donation_option = $_POST['donation_option'];
        $result = $db->update($id, $donation_option);
        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
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