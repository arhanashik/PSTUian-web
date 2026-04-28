<?php
require_once './constant.php';
require_once './db/teacher_db.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new TeacherDb();
 
switch ($_GET['call']) 
{
    case 'getAll':
        if($_GET['faculty_id'] === null || empty($_GET['faculty_id'])) break;

        $faculty_id = $_GET['faculty_id'];
        $data = $db->getAll($faculty_id);
        if($data === false) 
        {
            $response['code'] = READ_FAILED;
            $response['message'] = 'No data found!';
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
        || $_POST['designation'] === null || strlen($_POST['designation']) <= 0 
        || $_POST['department'] === null || strlen($_POST['department']) <= 0 
        || $_POST['blood'] === null
        || $_POST['faculty_id'] === null || strlen($_POST['faculty_id']) <= 0) break;

        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $blood = $_POST['blood'];
        $faculty_id = $_POST['faculty_id'];
        
        $data = $db->update_academic_info($user_id, $name, $designation, $department, $blood, $faculty_id);
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
        || $_POST['old_email'] === null || strlen($_POST['old_email']) <= 0
        || $_POST['linked_in'] === null|| $_POST['fb_link'] === null) break;

        $user_id = $_POST['user_id'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $old_email = $_POST['old_email'];
        $linked_in = $_POST['linked_in'];
        $fb_link = $_POST['fb_link'];
        
        // if we need to change email, first check if already exists
        if($email !== $old_email && $db->getByEmail($email)) {
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        $data = $db->update_connect_info($user_id, $address, $phone, $email, $linked_in, $fb_link);
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
    
    default:
        break;
}

echo json_encode($response);