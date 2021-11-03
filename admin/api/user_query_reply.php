<?php
require_once './auth_validation.php';
require_once './db/user_query_db.php';
require_once './db/user_query_reply_db.php';
require_once './db/log_db.php';
require_once './util/util.php';
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
$logDb = new LogDb();
$util = new Util();
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
        $query = $queryDb->get($query_id);
        if(!$query || $query === null) {
            $response['message'] = 'Ops, query not found!';
            break;
        }
        if(!$util->isValidEmail($query['email'])) {
            $response['message'] = 'Ops, user email is not valid.';
            break;
        }
        if(!$util->sendUserQueryReplyEmail($query['email'], $query['query'], $reply)) {
            $response['message'] = 'Sorry, failed to send reply email. Please try again.';
            break;
        }
        $inserted = $db->insert($query_id, $admin_id, $reply);
        if($inserted === null || !$inserted) {
            $response['message'] = 'Sorry, failed to complete your request. Please try again.';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Replied successfullly!';
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