<?php
require_once './db/employee_db.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
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
            if(!$data || $data === null) 
            {
                $response['code'] = READ_FAILD;
                $response['message'] = 'No data found!';
            }
            else
            {
                $response['code'] = SUCCESS;
                $response['message'] = 'Total ' . count($data) . ' item(s)';
                $response['data'] = $data;
            }
            break;
		
        default:
            break;
    }
}

echo json_encode($response);