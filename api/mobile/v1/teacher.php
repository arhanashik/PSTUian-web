<?php
require_once './db/teacher_db.php';
 
$response = array();
$response['success'] = false;
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

    case 'get':
        if($_GET['id'] === null || strlen($_GET['id']) <= 0) break;

        $id = $_GET['id'];
        $data = $db->get($id);
        if($data === null || empty($data)) 
        {
            $response['message'] = 'No data found!';
            break;
        }
        unset($data['password']);
        $response['success'] = true;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        break; 

    case 'updateImageUrl':
        require_once './auth_validation.php';
        if($_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['image_url'] === null ||  strlen($_POST['image_url']) <= 0) break;

        $id = $_POST['id'];
        $image_url = $_POST['image_url'];
        $data = $db->update_image_url($id, $image_url);
        if(!$data || $data == 0) 
        {
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Profile picture changed successfullly!';
        }
        break;

    case 'updateName':
        require_once './auth_validation.php';
        if($_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['name'] === null ||  strlen($_POST['name']) <= 0) break;

        $id = $_POST['id'];
        $name = $_POST['name'];
        $data = $db->update_name($id, $name);
        if(!$data || $data == 0) 
        {
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Name changed successfullly!';
        }
        break;

    case 'updateBio':
        require_once './auth_validation.php';
        if($_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['bio'] === null ||  strlen($_POST['bio']) <= 0) break;

        $id = $_POST['id'];
        $bio = $_POST['bio'];
        $data = $db->update_bio($id, $bio);
        if(!$data || $data == 0) 
        {
            $response['message'] = 'Update failed!';
        }
        else
        {
            $response['success'] = true;
            $response['message'] = 'Name changed successfullly!';
        }
        break;

    case 'updateAcademicInfo':
        require_once './auth_validation.php';
        if($_POST['id'] === null || strlen($_POST['id']) <= 0
        || $_POST['name'] === null || strlen($_POST['name']) <= 0  
        || $_POST['designation'] === null || strlen($_POST['designation']) <= 0 
        || $_POST['department'] === null || strlen($_POST['department']) <= 0 
        || $_POST['blood'] === null
        || $_POST['faculty_id'] === null || strlen($_POST['faculty_id']) <= 0) break;

        $id = $_POST['id'];
        $name = $_POST['name'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $blood = $_POST['blood'];
        $faculty_id = $_POST['faculty_id'];
        
        $data = $db->update_academic_info($id, $name, $designation, $department, $blood, $faculty_id);
        if(!$data || $data == 0) 
        {
            $response['message'] = 'Update failed!';
        }
        else
        {
            $user = $db->get($id);
            unset($user['password']);
            $response['success'] = true;
            $response['message'] = 'Info changed successfullly!';
            $response['data'] = $user;
        }
        break;

    case 'updateConnectInfo':
        require_once './auth_validation.php';
        if($_POST['id'] === null || strlen($_POST['id']) <= 0 
        || $_POST['address'] === null || $_POST['phone'] === null
        || $_POST['email'] === null || strlen($_POST['email']) <= 0 
        || $_POST['old_email'] === null || strlen($_POST['old_email']) <= 0
        || $_POST['linked_in'] === null|| $_POST['fb_link'] === null) break;

        $id = $_POST['id'];
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
        $data = $db->update_connect_info($id, $address, $phone, $email, $linked_in, $fb_link);
        if(!$data || $data == 0) 
        {
            $response['message'] = 'Update failed!';
        }
        else
        {
            $user = $db->get($id);
            unset($user['password']);
            $response['success'] = true;
            $response['message'] = 'Info changed successfullly!';
            $response['data'] = $user;
        }
        break;
    
    default:
        break;
}

echo json_encode($response);