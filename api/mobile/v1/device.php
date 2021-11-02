<?php
require_once './db/device_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new DeviceDb();

switch ($call) 
{
    case 'register':
        if($_POST['id'] === null || strlen($_POST['id']) <= 0
        || $_POST['fcm_token'] === null || strlen($_POST['fcm_token']) <= 0 
        || $_POST['model'] === null
        || $_POST['android_version'] === null
        || $_POST['app_version_code'] === null
        || $_POST['app_version_name'] === null
        || $_POST['ip_address'] === null
        || $_POST['lat'] === null
        || $_POST['lng'] === null
        || $_POST['locale'] === null) break;

        $id = $_POST['id'];
        $fcm_token = $_POST['fcm_token'];
        $model = $_POST['model'];
        $android_version = $_POST['android_version'];
        $app_version_code = $_POST['app_version_code'];
        $app_version_name = $_POST['app_version_name'];
        $ip_address = $_POST['ip_address'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $locale = $_POST['locale'];

        $exists = $db->isAlreadyInsered($id);
        if($exists) {
            $result = $db->update($id, $fcm_token, $model, $android_version, $app_version_code, 
            $app_version_name, $ip_address, $lat, $lng, $locale);
        } else {
            $result = $db->insert($id, $fcm_token, $model, $android_version, $app_version_code, 
            $app_version_name, $ip_address, $lat, $lng, $locale);
        }
        
        $operation_type = $exists? 'updated' : 'registered';
        if($result === null || !$result) 
        {
            $response['message'] = "Sorry, device not $operation_type. Please try again.";
            break;
        }

        $response['success'] = true;
        $response['message'] = "Device $operation_type successfullly!";
        $response['data'] = $db->get($id);
        break;

    case 'updateFcmToken':
        if($_POST['device_id'] === null || strlen($_POST['device_id']) <= 0
        || $_POST['fcm_token'] === null || strlen($_POST['fcm_token']) <= 0) break;

        $device_id = $_POST['device_id'];
        $fcm_token = $_POST['fcm_token'];

        if(!$db->isAlreadyInsered($device_id)) {
            $response['message'] = 'Sorry, device not found!';
            break;
        }

        $updated = $db->update_fcm_token($device_id, $fcm_token);
        if($updated === null || !$updated) {
            $response['message'] = 'Sorry, failed to update registration token! 
            Please try again.';
            break;
        }

        $response['success'] = true;
        $response['message'] = 'Registration token updated successfullly!';
        $response['data'] = $db->get($device_id);
    default:
        break;
}

echo json_encode($response);