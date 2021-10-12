<?php
require_once './auth_validation.php';
require_once './db/faculty_db.php';
require_once './common_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new FacultyDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($_GET['call']) 
{
    case 'add':
        if(!isset($_POST['short_title']) || empty($_POST['short_title'])
        || !isset($_POST['title']) || empty($_POST['title'])) {
            break;
        }
        $short_title = $_POST['short_title'];
        $title = $_POST['title'];
        $db = new FacultyDb();
        $result = $db->insert($short_title, $title);

        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $result;
        break;

    case 'update':
        if(!isset($_POST['id']) || empty($_POST['id']) 
        || !isset($_POST['short_title']) || empty($_POST['short_title'])
        || !isset($_POST['title']) || empty($_POST['title'])) {
            break;
        }
        $id = $_POST['id'];
        $short_title = $_POST['short_title'];
        $title = $_POST['title'];
        $db = new FacultyDb();
        $result = $db->update($id, $short_title, $title);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        break;
    
    default:
        break;
}

echo json_encode($response);