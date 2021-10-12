<?php
require_once './auth_validation.php';
require_once './db/slider_db.php';
require_once './util/image_upload_util.php';
require_once './common_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new SliderDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($call) 
{
    case 'add':
        if(!isset($_POST['title']) || !isset($_FILES['image'])) {
            break;
        }
        //file upload error
        $fileError = $_FILES['image']['error'];
        if($fileError !== UPLOAD_ERR_OK){
            $response['message'] = "Sorry, there was an error uploading the image.";
            break;
        }

        $title = $_POST['title'];
        $image = $_FILES['image'];
        $util = new ImageUploadUtil();
        $extension = $util->getExtension($image);
        $target_file_name = round(microtime(true) * 1000) . '.' . $extension;
        //upload
        $upload_result = $util->uploadSlider($image, $target_file_name, $extension);
        //upload failed
        if($upload_result !== '') {
            $response = $upload_result;
            break;
        }
        //insert to db
        $result = $db->insert($title, $target_file_name);

        $response['success'] = true;
        $response['message'] = 'Inserted Successfully';
        $response['data'] = $result;
        break;

    case 'update':
        if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['title']) 
        || !isset($_POST['image_url']) || empty($_POST['image_url'])) {
            break;
        }
        $id = $_POST['id'];
        $title = $_POST['title'];
        $image_url = $_POST['image_url'];
        $result = $db->update($id, $title, $image_url);

        $response['success'] = true;
        $response['message'] = 'Updated Successfully';
        $response['data'] = $result;
        break;
    
    default:
        break;
}

echo json_encode($response);