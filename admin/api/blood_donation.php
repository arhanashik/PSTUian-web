<?php
require_once './auth_validation.php';
require_once './db/blood_donation_db.php';
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
$db = new BloodDonationDb();
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
        || $_POST['request_id'] === null
        || $_POST['date'] === null || strlen($_POST['date']) <= 0 
        || $_POST['info'] === null) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $info = $_POST['info'];

        if(strlen($request_id) <= 0) $request_id = 0;
        
        $insert_id = $db->insert($user_id, $user_type, $request_id, $date, $info);
        if(!$insert_id || $insert_id <= 0) 
        {
            $response['message'] = 'Insertion failed!' . $request_id;
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
        || $_POST['request_id'] === null
        || $_POST['date'] === null || strlen($_POST['date']) <= 0 
        || $_POST['info'] === null) break;

        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $info = $_POST['info'];

        if(strlen($request_id) <= 0) $request_id = 0;
        
        $result = $db->update($id, $user_id, $user_type, $request_id, $date, $info);
        if(!$result || $result <= 0) 
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
    
    default:
        break;
}

echo json_encode($response);