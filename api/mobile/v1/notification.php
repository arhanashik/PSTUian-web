<?php
require_once './db/notification_db.php';
require_once './db/auth_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new NotificationDb();
$authDb = new AuthDb();

switch ($call) 
{
    case 'getAll':
        $user_id = -1;
        $user_type = null;
        if($_GET['user_id'] !== null && strlen($_GET['user_id']) > 0 
        && $_GET['user_type'] !== null && strlen($_GET['user_type']) > 0) {
            $user_id = $_GET['user_id'];
            $user_type = $_GET['user_type'];
        }

        $device_id = -1;
        if($user_id !== -1 && $user_type !== null) {
            $auth = $authDb->getByUserIdAndType($user_id, $user_type);
            if($auth && $auth['device_id'] !== null && strlen($auth['device_id']) > 0) {
                $device_id = $auth['device_id'];
            }
        }

        $data = $db->getAll($device_id);
        if(empty($data)) 
        {
            $response['message'] = 'No data found!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Total ' . count($data) . ' item(s)';
            $response['data'] = $data;
        }
        break;
    
    default:
        break;
}

echo json_encode($response);