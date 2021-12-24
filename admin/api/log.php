<?php
require_once './auth_validation.php';
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
$db = new LogDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'add':
        break;

    case 'update':
        break;

    case 'getAll':
        $page = 1;
        $limit = 20;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }
        $response = $db->getAll($page, $limit);
        
        break;

    case 'removePermanent':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $log_data = json_encode($db->get($id));
        $result = $db->deletePermanent($id);
        if($result == null || !$result) {
            $response['message'] = 'Operation failed!';
            return $response;
        }
        $response['success'] = true;
        $response['message'] = 'Deleted Successfully';
        break;
    
    default:
        break;
}

echo json_encode($response);