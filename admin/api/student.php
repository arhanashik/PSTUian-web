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
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['reg']) || strlen($_POST['reg']) <= 0
        || !isset($_POST['batch_id']) || strlen($_POST['batch_id']) <= 0
        || !isset($_POST['session']) || strlen($_POST['session']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0) {
            break;
        }
        $name = $_POST['name'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $batch_id = $_POST['batch_id'];
        $session = $_POST['session'];
        $email = $_POST['email'];
        $faculty_id = $_POST['faculty_id'];
        if($db->isAlreadyInsered($id)) {
            $response['message'] = 'Account already exists!';
            break;
        }
        if($db->getByEmail($email)) {
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        //default password
        $password = md5($id);
        $result = $db->insert($name, $id, $reg, $email, $batch_id, $session, $faculty_id, $password);

        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $result;
        break;

    case 'update':
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['old_id']) || strlen($_POST['old_id']) <= 0
        || !isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['reg']) || strlen($_POST['reg']) <= 0
        || !isset($_POST['batch_id']) || strlen($_POST['batch_id']) <= 0
        || !isset($_POST['session']) || strlen($_POST['session']) <= 0
        || !isset($_POST['old_email']) || strlen($_POST['old_email']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0) {
            break;
        }
        $name = $_POST['name'];
        $old_id = $_POST['old_id'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $batch_id = $_POST['batch_id'];
        $session = $_POST['session'];
        $old_email = $_POST['old_email'];
        $email = $_POST['email'];
        $faculty_id = $_POST['faculty_id'];
        if($old_id !== $id && $db->get($id)) {
            $response['message'] = 'Ops, Account already exists for this id';
            break;
        }
        if($old_email !== $email && $db->getByEmail($email)) {
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        $result = $db->update($name, $old_id, $id, $reg, $email, $batch_id, $session, $faculty_id);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        break;
    
    default:
        break;
}

echo json_encode($response);