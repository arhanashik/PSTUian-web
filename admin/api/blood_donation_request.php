<?php
require_once './auth_validation.php';
require_once './db/blood_donation_request_db.php';
require_once './db/device_db.php';
require_once './util/fcm_util.php';
require_once './db/notification_db.php';
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
$db = new BloodDonationReqeuestDb();
$device_db = new DeviceDb();
$fcm_util = new FcmUtil();
$notification_db = new NotificationDb();
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
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0
        || $_POST['user_type'] === null || strlen($_POST['user_type']) <= 0  
        || $_POST['blood_group'] === null || strlen($_POST['blood_group']) <= 0 
        || $_POST['before_date'] === null || strlen($_POST['before_date']) <= 0 
        || $_POST['contact'] === null || strlen($_POST['contact']) <= 0 
        || $_POST['info'] === null) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $blood_group = $_POST['blood_group'];
        $before_date = $_POST['before_date'];
        $contact = $_POST['contact'];
        $info = $_POST['info'];
        
        $insert_id = $db->insert($user_id, $user_type, $blood_group, $before_date, $contact, $info);
        if(!$insert_id || $insert_id <= 0) 
        {
            $response['message'] = 'Insertion failed!';
            break;
        }

        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $insert_id;
        // add log
        $log_data = json_encode($db->getSingle($insert_id));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if($_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['user_id'] === null || strlen($_POST['user_id']) <= 0
        || $_POST['user_type'] === null || strlen($_POST['user_type']) <= 0  
        || $_POST['blood_group'] === null || strlen($_POST['blood_group']) <= 0 
        || $_POST['before_date'] === null || strlen($_POST['before_date']) <= 0 
        || $_POST['contact'] === null || strlen($_POST['contact']) <= 0 
        || $_POST['info'] === null) break;

        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $blood_group = $_POST['blood_group'];
        $before_date = $_POST['before_date'];
        $contact = $_POST['contact'];
        $info = $_POST['info'];
        
        $data = $db->update($id, $user_id, $user_type, $blood_group, $before_date, $contact, $info);
        if(!$data || $data <= 0) 
        {
            $response['message'] = 'Update failed!';
            break;
        }

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $id;
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

        $item = $db->getSingle($id);
        // add log
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'confirm', $log_data);
        }

        //send notification
        $device_id = "all";
        $title = "Need " . $item['blood_group'] . " blood";
        $body = $item['info'];
        $type = "blood_donation";
        $message = $item['info'] . " \nPlease contact at: " . $item['contact'];
        $tokens = $device_db->getAllTokens();
        if(empty($tokens)) {
            $response['data'] = 'No receiver found!';
            break;
        }
        // create necessary data for notification
        $notification = $fcm_util->createPushNotification(
            $title, $body, FCM_CLICK_ACTION["open_blood_donation_request"]
        );
        $data = $fcm_util->createPushData($type, $title, $message);
        // send notification
        $resJson = $fcm_util->sendPush($tokens, $notification, $data);
        // check notification result
        if($resJson === false) {
            $response['data'] = 'Failed to send notification!';
            break;
        }
        $resArr = json_decode($resJson, true);
        $success = $resArr['success'];
        if(!$success || $success <= 0) {
            $response['data'] = 'Failed to send notification!';
            break;
        }
        // insert notification result into database
        $data = "Target device: " . count($tokens) . " - Successful: " . $success;
        $inserted = $notification_db->insert($device_id, $type, $title, $message, $data);
        if($inserted === null || !$inserted) {
            $response['data'] = 'Sorry, operation failed. Please try again.';
            break;
        }
        $response['data'] = 'Notifcation sent to ' . $success . ' device(s)';
        // add log
        $log_data = json_encode($notification_db->getSingle($inserted));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'send', $log_data);
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