<?php
require_once './db/faculty_db.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new FacultyDb();
 
switch ($_GET['call']) 
{
    case 'getAll':
        $data = $db->getAll();
        if(!$data) {
            $response['code'] = READ_FAILD;
            $response['message'] = 'No data found!';
        } else {
            $response['code'] = SUCCESS;
            $response['message'] = 'Total ' . count($data) . ' item(s)';
            $response['data'] = $data;
        }
        break;

    case 'get':
        if($_GET['id'] === null || strlen($_GET['id']) <= 0) break;

        $id = $_GET['id'];
        $data = $db->get($id);
        if($data === null || !$data) {
            $response['code'] = READ_FAILD;
            $response['message'] = 'No data found!';
        } else {
            $response['code'] = SUCCESS;
            $response['message'] = 'Data found';
            $response['data'] = $data;
        }
        break;
    
    default:
        break;
}

echo json_encode($response);