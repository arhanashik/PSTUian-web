<?php
require_once './auth_validation.php';
require_once './db/employee_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';
 
if (isset($_GET['call'])) 
{
    switch ($_GET['call']) 
    {
        case 'getAll':
            if(!isset($_GET['faculty_id']) || strlen($_GET['faculty_id']) <= 0) break;

            $faculty_id = $_GET['faculty_id'];
            $db = new EmplyeeDb();
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
		
        default:
            break;
    }
}

echo json_encode($response);