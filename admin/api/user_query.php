<?php
require_once './auth_validation.php';
require_once './db/user_query_db.php';
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
$db = new UserQueryDb();
$logDb = new LogDb();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'add':
        if($_POST['name'] === null || strlen($_POST['name']) <= 0
        || $_POST['email'] === null ||  strlen($_POST['email']) <= 0 
        || $_POST['type'] === null ||  strlen($_POST['type']) <= 0 
        || $_POST['query'] === null ||  strlen($_POST['query']) <= 0) break;

        $name = $_POST['name'];
        $email = $_POST['email'];
        $type = $_POST['type'];
        $query = $_POST['query'];
        $inserted = $db->insert($name, $email, $type, $query);
        if($inserted === null || !$inserted) {
            $response['message'] = 'Sorry, failed to complete your request. Please try again.';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Thanks, your request is completed successfully and is our top priority. We will get back to you as soon as possible!';
        // add log
        $log_data = json_encode($db->getSingle($inserted));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;
    
    default:
        break;
}

echo json_encode($response);