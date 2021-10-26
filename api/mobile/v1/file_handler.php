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
        if(!isset($_POST['name']) || !isset($_FILES['file'])) {
            break;
        }
        $name = $_POST['name'];
        $file = $_FILES['file'];
        $extension = "jpeg";
        $target_file = ROOT_DIR . '/' . STUDENT_IMAGE_PATH . $name;
        //upload
        $upload_result = $util->uploadImage($file, $target_file, $extension);
        //upload failed
        if($upload_result !== '') {
            $response['message'] = $upload_result;
            break;
        }
        $url = BASE_URL . STUDENT_IMAGE_PATH . $name;

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