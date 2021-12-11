<?php
require_once './auth_validation.php';
require_once './db/notification_db.php';
require_once './db/device_db.php';
require_once './db/log_db.php';
require_once './common_request.php';
require_once './util/fcm_util.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$common_request = new CommonRequest();
$db = new NotificationDb();
$device_db = new DeviceDb();
$fcm_util = new FcmUtil();
$logDb = new LogDb();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
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

    case 'send':
        if($_POST['device_id'] === null || strlen($_POST['device_id']) <= 0
        || $_POST['type'] === null || strlen($_POST['type']) <= 0
        || $_POST['title'] === null ||  strlen($_POST['title']) <= 0 
        || $_POST['message'] === null ||  strlen($_POST['message']) <= 0) break;

        $device_id = $_POST['device_id'];
        $type = $_POST['type'];
        $title = $_POST['title'];
        $message = $_POST['message'];

        // if device_id is = all, send notification to all devices.
        // else send to the specific device.
        $tokens = array();
        if($device_id == 'all') {
            $tokens = $device_db->getAllTokens();
        } else {
            $token = $device_db->getToken($device_id);
            if($token !== null && $token) {
                array_push($tokens, $token);
            }
        }

        if(empty($tokens)) {
            $response['message'] = 'No receiver found!';
            break;
        }

        // create necessary data for notification
        $body = $message;
        $notification = $fcm_util->createPushNotification(
            $title, $body, FCM_CLICK_ACTION["open_notifications"]
        );
        $data = $fcm_util->createPushData($type, $title, $message);
        // send notification
        $resJson = $fcm_util->sendPush($tokens, $notification, $data);
        // check notification result
        if($resJson === false) {
            $response['message'] = 'Failed to send notification!';
            break;
        }
        $resArr = json_decode($resJson, true);
        $success = $resArr['success'];
        if(!$success || $success <= 0) {
            $response['message'] = 'Failed to send notification!';
            break;
        }
        // $messageIds = $resArr['results']; //array of message ids if success
        // $messageId = $messageIds[0]['message_id'];
        // insert notification result into database
        $data = "Target device: " . count($tokens) . " - Successful: " . $success;
        $inserted = $db->insert($device_id, $type, $title, $message, $data);
        if($inserted === null || !$inserted) {
            $response['message'] = 'Sorry, operation failed. Please try again.';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Notifcation sent to ' . $success . ' device(s)';
        // add log
        $log_data = json_encode($db->getSingle($inserted));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'send', $log_data);
        }
        break;

    case 'update':
        if($_POST['id'] === null || strlen($_POST['id']) <= 0
        || $_POST['device_id'] === null || strlen($_POST['device_id']) <= 0
        || $_POST['type'] === null || strlen($_POST['type']) <= 0
        || $_POST['title'] === null ||  strlen($_POST['title']) <= 0 
        || $_POST['message'] === null ||  strlen($_POST['message']) <= 0) break;

        $id = $_POST['id'];
        $device_id = $_POST['device_id'];
        $type = $_POST['type'];
        $title = $_POST['title'];
        $message = $_POST['message'];

        $result = $db->update($id, $device_id, $type, $title, $message);
        if(!$result || $result <= 0) {
            $response['message'] = 'Updated failed';
            break;
        }

        $item = $db->getSingle($id);
        $response['success'] = true;
        $response['message'] = 'Updated successfully';
        $response['data'] = $item;

        // add log
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'update', $log_data);
        }

        break;
    
    default:
        break;
}

echo json_encode($response);