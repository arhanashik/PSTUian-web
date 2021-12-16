<?php
require_once './auth_validation.php';
require_once './db/check_in_db.php';
require_once './db/check_in_location_db.php';
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
$db = new CheckInDb();
$checkInLocationDb = new CheckInLocationDb();
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
        if(!isset($_POST['location_id']) || strlen($_POST['location_id']) <= 0
        || !isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0) break;

        $location_id = $_POST['location_id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];

        // check if valid location
        $location = $checkInLocationDb->get($location_id);
        if(!$location || empty($location)) {
            $response['message'] = 'Invalid location!';
            break;
        }
        
        $oldCheckIn = $db->getByUser($location_id, $user_id, $user_type);
        if($db->getByUser($location_id, $user_id, $user_type)) 
        { 
            $response['message'] = 'Check in already exists!';
            break;
        } 
        // new check in
        $check_in_id = $db->insert($location_id, $user_id, $user_type);
        if(!$check_in_id || $check_in_id <= 0) 
        {
            $response['message'] = 'Sorry, check in failed!';
            break;
        }

        // increment the count for the location
        $checkInLocationDb->incrementCount($location_id);

        $item = $db->getSingle($check_in_id);
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
        || !isset($_POST['location_id']) || strlen($_POST['location_id']) <= 0
        || !isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['count']) || strlen($_POST['count']) <= 0
        || !isset($_POST['privacy']) || strlen($_POST['privacy']) <= 0) break;

        $id = $_POST['id'];
        $location_id = $_POST['location_id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $count = $_POST['count'];
        $privacy = $_POST['privacy'];
        
        $data = $db->update($id, $location_id, $user_id, $user_type, $count, $privacy);
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
        $result = $db->updateVerification($id, 1);

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
        $result = $db->updateVerification($id, 0);

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