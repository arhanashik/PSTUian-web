<?php
require_once './constant.php';
require_once './util/file_upload_util.php';

$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$util = new FileUploadUtil();

switch ($call) 
{
    case 'uploadImage':
        require_once './auth_validation.php';
        if(!isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_FILES['file'])) {
            break;
        }
        $user_type = $_POST['user_type'];
        $name = $_POST['name'];
        $file = $_FILES['file'];
        $extension = "jpeg";
        if($user_type !== 'student' && $user_type !== 'teacher') {
            $response['message'] = 'Invalid user!';
            break;
        }
        $sub_dir = ($user_type === 'student')? STUDENT_IMAGE_PATH : TEACHER_IMAGE_PATH;
        $target_file = ROOT_DIR . '/' . $sub_dir . $name;
        //upload
        $upload_result = $util->uploadImage($file, $target_file, $extension);
        //upload failed
        if($upload_result !== '') {
            $response['message'] = $upload_result;
            break;
        }
        $url = BASE_URL . $sub_dir . $name;

        $response['success'] = true;
        $response['message'] = 'File Uploaded Successfully';
        $response['data'] = $url;
        break;

    case 'uploadPdf':
        require_once './auth_validation.php';
        if(!isset($_POST['name']) || !isset($_FILES['file'])) {
            break;
        }
        $name = $_POST['name'];
        $file = $_FILES['file'];
        $extension = $util->getExtension($file);
        $target_file = ROOT_DIR . '/' . CV_PATH . $name;
        //upload
        $upload_result = $util->uploadPdf($file, $target_file, $extension);
        //upload failed
        if($upload_result !== '') {
            $response['message'] = $upload_result;
            break;
        }
        $url = BASE_URL . CV_PATH . $name;

        $response['success'] = true;
        $response['message'] = 'File Uploaded Successfully';
        $response['data'] = $url;
        break;
    
    default:
        break;
}

echo json_encode($response);