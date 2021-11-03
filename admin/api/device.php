<?php
require_once './auth_validation.php';
require_once './db/device_db.php';
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
$common_request = new CommonRequest();
$db = new DeviceDb();
$logDb = new LogDb();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'update':
        if($_POST['id'] === null || strlen($_POST['id']) <= 0
        || $_POST['fcm_token'] === null || strlen($_POST['fcm_token']) <= 0 
        || $_POST['model'] === null
        || $_POST['android_version'] === null
        || $_POST['ios_version'] === null
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
        $ios_version = $_POST['ios_version'];
        $app_version_code = $_POST['app_version_code'];
        $app_version_name = $_POST['app_version_name'];
        $ip_address = $_POST['ip_address'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $locale = $_POST['locale'];

        if(!$db->isAlreadyInsered($id)) {
            $response['message'] = "Sorry, device not found!";
            break;
        }
        $result = $db->update($id, $fcm_token, $model, $android_version, $ios_version, 
        $app_version_code, $app_version_name, $ip_address, $lat, $lng, $locale);

        if($result === null || !$result) 
        {
            $response['message'] = "Sorry, device not updated. Please try again.";
            break;
        }

        $response['success'] = true;
        $response['message'] = "Device updated successfullly!";
        $response['data'] = $db->get($id);
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