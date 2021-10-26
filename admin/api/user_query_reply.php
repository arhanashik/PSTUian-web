<?php
require_once './auth_validation.php';
require_once './db/user_query_db.php';
require_once './db/user_query_reply_db.php';
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
$queryDb = new UserQueryDb();
$db = new UserQueryReplyDb();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'getAll':
        if(!isset($_GET['query_id']) || strlen($_GET['query_id']) <= 0) {
            break;
        }
        $query_id = $_GET['query_id'];

        $response = $db->getAllByQueryId($query_id);
        break;

    case 'add':
        if($_POST['query_id'] === null || strlen($_POST['query_id']) <= 0 
        || $_POST['admin_id'] === null ||  strlen($_POST['admin_id']) <= 0 
        || $_POST['reply'] === null ||  strlen($_POST['reply']) <= 0) break;

        $query_id = $_POST['query_id'];
        $admin_id = $_POST['admin_id'];
        $reply = $_POST['reply'];
        $inserted = $db->insert($query_id, $admin_id, $reply);
        if($inserted === null || !$inserted) 
        {
            $response['message'] = 'Sorry, failed to complete your request. Please try again.';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Reply added successfullly!';
        }
        break;
    
    default:
        break;
}

echo json_encode($response);