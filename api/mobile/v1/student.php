<?php
require_once './db/student_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';
 
if (isset($_GET['call'])) 
{
    switch ($_GET['call']) 
    {
        case 'getAll':
            if($_GET['faculty_id'] === null || empty($_GET['faculty_id']) 
            || $_GET['batch_id'] === null ||  empty($_GET['batch_id'])) break;

            $faculty_id = $_GET['faculty_id'];
            $batch_id = $_GET['batch_id'];
            $db = new StudentDb();
            $data = $db->getAll($faculty_id, $batch_id);
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