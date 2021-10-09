<?php
require_once './validate_request.php';
require_once './db/slider_db.php';
require_once './util/image_upload_util.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new SliderDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            $response = $db->getAll();
            break;

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

        case 'delete':
            if(!isset($_POST['id']) || empty($_POST['id'])) {
                break;
            }
            $id = $_POST['id'];
            $result = $db->delete($id);

            $response['success'] = true;
            $response['message'] = 'Deleted Successfully';
            $response['data'] = $result;
            break;

        case 'restore':
            if(!isset($_POST['id']) || empty($_POST['id'])) {
                break;
            }
            $id = $_POST['id'];
            $result = $db->restore($id);

            $response['success'] = true;
            $response['message'] = 'Restored Successfully';
            $response['data'] = $result;
            break;
		
        default:
            break;
    }
}

echo json_encode($response);