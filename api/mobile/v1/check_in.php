<?php
require_once './auth_validation.php';
require_once './db/check_in_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new CheckInDb();
 
switch ($_GET['call']) 
{
    case 'getAll':
        $page = 1;
        $limit = 25;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }

        $data = $db->getAll($page, $limit);
        if($data === null || empty($data)) 
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

    case 'get':
        if($_GET['id'] === null || strlen($_GET['id']) <= 0) break;

        $id = $_GET['id'];
        $data = $db->get($id);
        if($data === null || empty($data)) 
        {
            $response['message'] = 'No data found!';
            break;
        }
        unset($data['password']);
        $response['success'] = true;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        break;

    case 'checkIn':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0
        || $_POST['user_type'] === null || strlen($_POST['user_type']) <= 0) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $info = $_POST['info'];
        
        $insert_id = $db->insert($user_id, $user_type, $request_id, $date, $info);
        if(!$insert_id || $insert_id <= 0) 
        {
            $response['message'] = 'Sorry, failed to create donation request!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Donation request created successfullly!';
            $response['date'] = $db->getById($insert_id);
        }
        break;

    case 'visibility':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0
        || $_POST['user_type'] === null || strlen($_POST['user_type']) <= 0
        || $_POST['visibility'] === null || strlen($_POST['visibility']) <= 0) break;

        $id = $_POST['id'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $visibility = $_POST['visibility'];
        
        $result = $db->update($id, $request_id, $date, $info);
        if(!$result || $result <= 0) 
        {
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Info changed successfullly!';
            $response['date'] = $db->getById($id);
        }
        break;
    
    default:
        break;
}

echo json_encode($response);