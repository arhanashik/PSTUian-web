<?php
require_once './auth_validator.php';
require_once './db/check_in_location_db.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new CheckInLocationDb();
 
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
        $sorting_order = 'ASC';
        $sorting_col = 'created_at';
        $data = $db->getAllPaged($page, $limit, $sorting_order, $sorting_col);
        if ($data === false) {
            $response['code'] = READ_FAILED;
            $response['message'] = 'Database error occurred.';
            return;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
        break;

    case 'get':
        if(!isset($_GET['id']) || strlen($_GET['id']) <= 0) break;

        $id = $_GET['id'];

        $data = $db->get($id);
        if($data === null || empty($data)) 
        {
            $response['code'] = READ_FAILED;
            $response['message'] = 'No data found!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        break;

    case 'search':
        if(!isset($_GET['query']) || $_GET['query'] === null) break;
        $query = $_GET['query'];

        $page = 1;
        $limit = 10;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }

        $data = $db->search($query, $page, $limit);
        if($data === null || empty($data)) 
        {
            $response['code'] = READ_FAILED;
            $response['message'] = 'No data found!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
        break;

    case 'insert':
        if(!isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['details']) 
        || !isset($_POST['image_url'])
        || !isset($_POST['link'])) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $name = $_POST['name'];
        $details = $_POST['details'];
        $image_url = $_POST['image_url'];
        $link = $_POST['link'];

        $old_item = $db->getByName($name);
        if($old_item && !empty($old_item)) {
            $response['message'] = 'Location already exists!';
            break;
        }
        $insert_id = $db->insert($user_id, $user_type, $name, $details, $image_url, $link);
        if(!$insert_id || $insert_id <= 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Faild to create new location!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'New location created successfully!';
        $response['data'] = $db->get($insert_id);
        break;
    
    default:
        break;
}

echo json_encode($response);