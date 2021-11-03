<?php
require_once './auth_validation.php';
require_once './db/config_db.php';
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
$db = new ConfigDb();
$logDb = new LogDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'getLatest':
        $config = $db->getLatest();
        if(!$config) 
        {
            $response['message'] = 'No data found!';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Latest config';
        $response['data'] = $config;
        break;

    case 'add':
        if(!isset($_POST['android_version']) || strlen($_POST['android_version']) <= 0
        || !isset($_POST['ios_version']) || strlen($_POST['ios_version']) <= 0
        || !isset($_POST['data_refresh_version']) || strlen($_POST['data_refresh_version']) <= 0
        || !isset($_POST['api_version']) || strlen($_POST['api_version']) <= 0
        || !isset($_POST['admin_api_version']) || strlen($_POST['admin_api_version']) <= 0
        || !isset($_POST['force_refresh']) || strlen($_POST['force_refresh']) <= 0 
        || !isset($_POST['force_update']) || strlen($_POST['force_update']) <= 0) {
            break;
        }
        $android_version = $_POST['android_version'];
        $ios_version = $_POST['ios_version'];
        $api_version = $_POST['api_version'];
        $data_refresh_version = $_POST['data_refresh_version'];
        $admin_api_version = $_POST['admin_api_version'];
        $force_refresh = $_POST['force_refresh'];
        $force_update = $_POST['force_update'];
        $result = $db->insert($android_version, $ios_version, $data_refresh_version, 
        $api_version, $admin_api_version, $force_refresh, $force_update);

        $response['success'] = true;
        $response['message'] = 'Added Successfully';
        $response['data'] = $result;
        // add log
        $log_data = json_encode($db->getSingle($result));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if(!isset($_POST['id']) || empty($_POST['id']) 
        || !isset($_POST['android_version']) || empty($_POST['android_version']) 
        || !isset($_POST['ios_version']) || empty($_POST['ios_version']) 
        || !isset($_POST['data_refresh_version']) || empty($_POST['data_refresh_version']) 
        || !isset($_POST['api_version']) || empty($_POST['api_version']) 
        || !isset($_POST['admin_api_version']) || empty($_POST['admin_api_version']) 
        || !isset($_POST['force_refresh']) || strlen($_POST['force_refresh']) <= 0 
        || !isset($_POST['force_update']) || strlen($_POST['force_update']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $android_version = $_POST['android_version'];
        $ios_version = $_POST['ios_version'];
        $data_refresh_version = $_POST['data_refresh_version'];
        $api_version = $_POST['api_version'];
        $ios_version = $_POST['ios_version'];
        $admin_api_version = $_POST['admin_api_version'];
        $force_refresh = $_POST['force_refresh'];
        $force_update = $_POST['force_update'];
        $result = $db->update($id, $android_version, $ios_version, $data_refresh_version, 
        $api_version, $admin_api_version, $force_refresh, $force_update);

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
    
    default:
        break;
}

echo json_encode($response);