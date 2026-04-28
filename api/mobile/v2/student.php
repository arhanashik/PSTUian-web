<?php
require_once './db/student_db.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new StudentDb();

switch ($call) 
{
    case 'getAll':
        if(!isset($_GET['faculty_id']) || strlen($_GET['faculty_id']) <= 0 
        || !isset($_GET['batch_id']) ||  strlen($_GET['batch_id']) <= 0) break;

        $faculty_id = $_GET['faculty_id'];
        $batch_id = $_GET['batch_id'];
        $page = $_GET['page'];
        $limit = $_GET['limit'];

        $data = $db->getAllByFacultyAndBatch($faculty_id, $batch_id, $page, $limit);

        if ($data === false) {
            $response['code'] = READ_FAILED;
            $response['message'] = 'Database error occurred.';
            return;
        }

        $response['code'] = SUCCESS;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
        break;

    case 'get':
        if($_GET['id'] === null || strlen($_GET['id']) <= 0) break;

        $id = $_GET['id'];
        $data = $db->get($id);
        if($data === null || empty($data)) 
        {
            $response['code'] = USER_NOT_FOUND;
            $response['message'] = 'No data found!';
            break;
        }
        unset($data['password']);

        $response['code'] = SUCCESS;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        break;

    case 'getByEmail':
        if($_GET['email'] === null || strlen($_GET['email']) <= 0) break;

        $email = $_GET['email'];
        $data = $db->getByEmail($email);
        if($data === null || empty($data)) 
        {
            $response['code'] = USER_NOT_FOUND;
            $response['message'] = 'No data found!';
            break;
        }
        unset($data['password']);

        $response['code'] = SUCCESS;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        break;

    case 'updateImageUrl':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0 
        || $_POST['image_url'] === null ||  strlen($_POST['image_url']) <= 0) break;

        $user_id = $_POST['user_id'];
        $image_url = $_POST['image_url'];
        $data = $db->update_image_url($user_id, $image_url);
        if(!$data || $data == 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['code'] = SUCCESS;
            $response['data'] = $image_url;
        }
        break;

    case 'updateName':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0 
        || $_POST['name'] === null ||  strlen($_POST['name']) <= 0) break;

        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $data = $db->update_name($user_id, $name);
        if(!$data || $data == 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['code'] = SUCCESS;
            $response['data'] = $name;
        }
        break;

    case 'updateBio':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0 
        || $_POST['bio'] === null ||  strlen($_POST['bio']) <= 0) break;

        $user_id = $_POST['user_id'];
        $bio = $_POST['bio'];
        $data = $db->update_bio($user_id, $bio);
        if(!$data || $data == 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['code'] = SUCCESS;
            $response['data'] = $bio;
        }
        break;

    case 'updateAcademicInfo':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0 
        || $_POST['name'] === null || strlen($_POST['name']) <= 0 
        || $_POST['old_id'] === null || strlen($_POST['old_id']) <= 0 
        || $_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['reg'] === null || strlen($_POST['reg']) <= 0 
        || $_POST['blood'] === null
        || $_POST['faculty_id'] === null || strlen($_POST['faculty_id']) <= 0 
        || $_POST['session'] === null || strlen($_POST['session']) <= 0 
        || $_POST['batch_id'] === null ||  strlen($_POST['batch_id']) <= 0) break;

        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $old_id = $_POST['old_id'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $blood = $_POST['blood'];
        $faculty_id = $_POST['faculty_id'];
        $session = $_POST['session'];
        $batch_id = $_POST['batch_id'];

        // if we need to change the id, first check if already exists
        if($old_id !== $id && $db->get($id)) {
            $response['code'] = USER_ALREADY_EXIST;   
            $response['message'] = 'Ops, Account already exists for this id';
            break;
        }
        
        $data = $db->update_academic_info($user_id, $name, $id, $reg, $blood, $faculty_id, $session, $batch_id);
        if(!$data || $data <= 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
            return;
        }
        $user = $db->getByUserId($user_id);

        $response['code'] = SUCCESS;
        $response['message'] = 'Info changed successfullly!';
        $response['data'] = $user;
        break;

    case 'updateConnectInfo':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0 
        || $_POST['address'] === null || $_POST['phone'] === null
        || $_POST['email'] === null || strlen($_POST['email']) <= 0 
        || $_POST['old_email'] === null || $_POST['cv_link'] === null 
        || $_POST['linked_in'] === null|| $_POST['fb_link'] === null) break;

        $user_id = $_POST['user_id'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $old_email = $_POST['old_email'];
        $email = $_POST['email'];
        $cv_link = $_POST['cv_link'];
        $linked_in = $_POST['linked_in'];
        $fb_link = $_POST['fb_link'];
        
        // if we need to change email, first check if already exists
        if($email !== $old_email && $db->getByEmail($email)) {
            $response['code'] = USER_ALREADY_EXIST;  
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }

        $data = $db->update_connect_info($user_id, $address, $phone, $email, $cv_link, $linked_in, $fb_link);
        if(!$data || $data <= 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
            break;
        }
        $user = $db->getByUserId($user_id);

        $response['code'] = SUCCESS;
        $response['message'] = 'Info changed successfullly!';
        $response['data'] = $user;
        break;

    case 'updateCv':
        if($_POST['user_id'] === null || strlen($_POST['user_id']) <= 0 
        || $_POST['cv_link'] === null) break;

        $user_id = $_POST['user_id'];
        $cv_link = $_POST['cv_link'];
        $data = $db->update_cv($user_id, $cv_link);
        if(!$data || $data == 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
            break;
        }

        $response['code'] = SUCCESS;
        $response['data'] = $cv_link;
        break;
    
    default:
        break;
}

echo json_encode($response);