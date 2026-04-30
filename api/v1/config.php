<?php
require_once './db/config_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new ConfigDb();

switch ($call) 
{
    case 'getLatest':
        $config = $db->getLatest();
        if(!$config) 
        {
            $response['message'] = 'No data found!';
            break;
        }
        $response['success'] = true;
        $response['message'] = 'Latest config';
        $response['data'] = $config;
        break;
    
    default:
        break;
}

echo json_encode($response);