<?php
require_once './db/device_db.php';
require_once './util/util.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new DeviceDb();
$util = new Util();

switch ($call) 
{
    case 'getAll':
        require_once './auth_validation.php';
        if(!isset($_GET['user_id']) || strlen($_GET['user_id']) <= 0
        || !isset($_GET['user_type']) || strlen($_GET['user_type']) <= 0) break;

        $user_id = $_GET['user_id'];
        $user_type = $_GET['user_type'];

        $page = 1;
        $limit = 25;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }

        $data = $db->getAllByUser($user_id, $user_type, $page, $limit);
        if(!$data || empty($data))
        {
            $response['message'] = 'No data found!';
            return;
        }
        $response['success'] = true;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
        break;

    case 'register':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['fcm_token']) || strlen($_POST['fcm_token']) <= 0) break;

        $id = $_POST['id'];
        $fcm_token = $_POST['fcm_token'];
        $model = isset($_POST['model']) ? $_POST['model'] : '';
        $android_version = isset($_POST['android_version']) ? $_POST['android_version'] : '';
        $app_version_code = isset($_POST['app_version_code']) ? $_POST['app_version_code'] : 0;
        $app_version_name = isset($_POST['app_version_name']) ? $_POST['app_version_name'] : '';
        $ip_address = isset($_POST['ip_address']) ? $_POST['ip_address'] : $util->getIp();
        $lat = isset($_POST['lat']) ? $_POST['lat'] : '0:0';
        $lng = isset($_POST['lng']) ? $_POST['lng'] : '0:0';
        $locale = isset($_POST['locale']) ? $_POST['locale'] : 'en';

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
        if(!isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0
        || !isset($_POST['fcm_token']) || strlen($_POST['fcm_token']) <= 0) break;

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