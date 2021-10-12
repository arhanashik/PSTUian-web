<?php
require_once './auth_validation.php';
require_once './db/student_db.php';
require_once './common_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new StudentDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($_GET['call']) 
{
    case 'getAll':
        if(isset($_GET['faculty_id']) && isset($_GET['batch_id'])) {
            $faculty_id = $_GET['faculty_id'];
            $batch_id = $_GET['batch_id'];
            $response = $db->getAllByFacultyAndBatch($faculty_id, $batch_id);
            break;
        }
        if(isset($_GET['faculty_id'])) {
            $faculty_id = $_GET['faculty_id'];
            $response = $db->getAllByFaculty($faculty_id);
        }
        break;

    case 'add':
        if(!isset($_POST['name']) || empty($_POST['name'])
        || !isset($_POST['id']) || empty($_POST['id'])
        || !isset($_POST['reg']) || empty($_POST['reg'])
        || !isset($_POST['batch_id']) || empty($_POST['batch_id'])
        || !isset($_POST['session']) || empty($_POST['session'])
        || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
            break;
        }
        $name = $_POST['name'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $batch_id = $_POST['batch_id'];
        $session = $_POST['session'];
        $faculty_id = $_POST['faculty_id'];
        if($db->isAlreadyInsered($id)) {
            $response['message'] = 'Account already exists!';
            break;
        }
        $result = $db->insert($name, $id, $reg, $batch_id, $session, $faculty_id);

        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $result;
        break;

    case 'update':
        if(!isset($_POST['name']) || empty($_POST['name'])
        || !isset($_POST['id']) || empty($_POST['id'])
        || !isset($_POST['reg']) || empty($_POST['reg'])
        || !isset($_POST['batch_id']) || empty($_POST['batch_id'])
        || !isset($_POST['session']) || empty($_POST['session'])
        || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
            break;
        }
        $name = $_POST['name'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $batch_id = $_POST['batch_id'];
        $session = $_POST['session'];
        $faculty_id = $_POST['faculty_id'];
        $result = $db->update($name, $id, $reg, $batch_id, $session, $faculty_id);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        break;
    
    default:
        break;
}

echo json_encode($response);