<?php
require_once './db/teacher_db.php';
require_once './auth_validation.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new BloodDonationDb();
 
switch ($_GET['call']) 
{
    case 'getAll':
        $page = 1;
        $limit = 25;
        if($_GET['page'] !== null && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if($_GET['limit'] !== null && strlen($_GET['limit']) > 0) {
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

    case 'insert':
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

    case 'update':
        if($_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['request_id'] === null
        || $_POST['date'] === null || strlen($_POST['date']) <= 0 
        || $_POST['info'] === null) break;

        $id = $_POST['id'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $info = $_POST['info'];
        
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

    case 'delete':
        if($_POST['id'] === null || strlen($_POST['id']) <= 0) break;

        $result = $db->delete($id);
        if(!$result || $result <= 0) 
        {
            $response['message'] = 'Failed to delete the request!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Deleted successfullly!';
        }
        break;
    
    default:
        break;
}

echo json_encode($response);