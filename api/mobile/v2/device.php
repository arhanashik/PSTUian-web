<?php
require_once './db/device_db.php';
require_once './util/util.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
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
        if(!$data) {
            $response['code'] = READ_FAILED;
            $response['message'] = 'No data found!';
            return;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
        break;

    case 'register':
        if(!isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0
        || !isset($_POST['fcm_token']) || !isset($_POST['model']) 
        || !isset($_POST['platform']) || !isset($_POST['app_version_code']) 
        || !isset($_POST['app_version_name'])) {
            break;
        }

        $device_id = $_POST['device_id'];
        $fcm_token = $_POST['fcm_token'];
        $model = $_POST['model'];
        $platform = $_POST['platform'];
        $app_version_code = $_POST['app_version_code'];
        $app_version_name = $_POST['app_version_name'];
        $ipAddress = $util->getIp();
        $lat = isset($_POST['lat']) ? $_POST['lat'] : '0:0';
        $lng = isset($_POST['lng']) ? $_POST['lng'] : '0:0';
        $locale = isset($_POST['locale']) ? $_POST['locale'] : 'en';

        $exists = $db->isAlreadyInsered($device_id);
        if($exists) {
            $result = $db->update($device_id, $fcm_token, $model, $platform, $app_version_code, $app_version_name, 
            $ipAddress, $lat, $lng, $locale);
        } else {
            $result = $db->insert($device_id, $fcm_token, $model, $platform, $app_version_code, $app_version_name, 
            $ipAddress, $lat, $lng, $locale);
        }
        
        $operation_type = $exists? 'updated' : 'registered';
        if($result === null || !$result) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = "Sorry, device not $operation_type. Please try again.";
            break;
        }

        $device = $db->get($device_id);
        $response['code'] = SUCCESS;
        $response['message'] = "Device $operation_type successfullly!";
        $response['data'] = $db->mapToData($device);;
        break;

    default:
        break;
}

echo json_encode($response);