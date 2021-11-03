<?php
require_once './db/batch_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new BatchDb();
 
switch ($_GET['call']) 
{
    case 'getAll':
        if($_GET['faculty_id'] === null || strlen($_GET['faculty_id']) <= 0) break;

        $faculty_id = $_GET['faculty_id'];
        $data = $db->getAll($faculty_id);
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
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Data found';
            $response['data'] = $data;
        }
        break;
    
    default:
        break;
}

echo json_encode($response);