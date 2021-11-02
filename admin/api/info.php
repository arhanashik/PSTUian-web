<?php
require_once './auth_validation.php';
require_once './db/info_db.php';
require_once './common_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new InfoDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'add':
        if(!isset($_POST['donation_option']) || strlen($_POST['donation_option']) <= 0) {
            break;
        }
        $donation_option = $_POST['donation_option'];
        $result = $db->insert($donation_option);
        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        break;

    case 'update':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0 
        || !isset($_POST['donation_option']) || strlen($_POST['donation_option']) <= 0) {
            break;
        }
        $id = $_POST['id'];
        $donation_option = $_POST['donation_option'];
        $result = $db->update($id, $donation_option);
        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        break;
    
    default:
        break;
}

echo json_encode($response);