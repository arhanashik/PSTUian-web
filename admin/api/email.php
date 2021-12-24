<?php
require_once './auth_validation.php';
require_once './util/util.php';
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
$util = new Util();
$logDb = new LogDb();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'getAll':
        break;

    case 'send':
        if(!isset($_POST['sender']) || strlen($_POST['sender']) <= 0
        || !isset($_POST['receiver']) || strlen($_POST['receiver']) <= 0
        || !isset($_POST['title']) ||  strlen($_POST['title']) <= 0 
        || !isset($_POST['body']) ||  strlen($_POST['body']) <= 0) break;

        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];
        $title = $_POST['title'];
        $body = $_POST['body'];

        $result = $util->sendEmail($sender, $receiver, $title, $body);
        if($result !== '') { // failed
            $response['message'] = $result ;
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Email has been sent to ' . $receiver;
        // add log
        $item = array(
            "sender" => $sender,
            "receiver" => $receiver,
            "title" => $title,
            "body" => $body,
        );
        $log_data = json_encode($item);
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'send', $log_data);
        }
        break;

    case 'update':
        break;
    
    default:
        break;
}

echo json_encode($response);