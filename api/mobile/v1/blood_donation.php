<?php
require_once './auth_validation.php';
require_once './db/blood_donation_db.php';
 
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
        if(!isset($_GET['user_id']) || strlen($_GET['user_id']) <= 0
        || !isset($_GET['user_type']) || strlen($_GET['user_type']) <= 0) break;
        $user_id = $_GET['user_id'];
        $user_type = $_GET['user_type'];

        $page = 1;
        $limit = 25;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }

        $data = $db->getAll($user_id, $user_type, $page, $limit);
        if($data === null || empty($data)) 
        {
            $response['message'] = 'No data found!';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
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
        if(!isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0  
        || !isset($_POST['request_id'])
        || !isset($_POST['date']) || strlen($_POST['date']) <= 0 
        || !isset($_POST['info'])) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $info = $_POST['info'];
        
        $insert_id = $db->insert($user_id, $user_type, $request_id, $date, $info);
        if(!$insert_id || $insert_id <= 0) 
        {
            $response['message'] = 'Sorry, failed to create donation request!';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Donation request created successfullly!';
        $response['data'] = $db->getById($insert_id);
        break;

    case 'update':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0 
        || !isset($_POST['request_id'])
        || !isset($_POST['date']) || strlen($_POST['date']) <= 0 
        || !isset($_POST['info'])) break;

        $id = $_POST['id'];
        $request_id = $_POST['request_id'];
        $date = $_POST['date'];
        $info = $_POST['info'];
        
        $result = $db->update($id, $request_id, $date, $info);
        if(!$result || $result <= 0) 
        {
            $response['message'] = 'Update failed!';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Info changed successfullly!';
        $response['data'] = $db->getById($id);
        break;

    case 'delete':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) break;

        $id = $_POST['id'];
        
        $result = $db->delete($id);
        if(!$result || $result <= 0) 
        {
            $response['message'] = 'Failed to delete!';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Deleted successfullly!';
        $response['data'] = $id;
        break;
    
    default:
        break;
}

echo json_encode($response);