<?php
require_once './db/slider_db.php';
require_once './validate_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if ($validate && isset($_GET['call'])) 
{
    $db = new SliderDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            $response = $db->getAll();
            break;

        case 'add':
            $title = $_POST['title'];
            $image_url = $_POST['image_url'];
            $result = $db->insert($title, $image_url);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            $id = $_POST['id'];
            $title = $_POST['title'];
            $image_url = $_POST['image_url'];
            $result = $db->update($id, $title, $image_url);

            $response['success'] = true;
            $response['message'] = 'Updated Successfully';
            $response['data'] = $result;
            break;

        case 'delete':
            $id = $_POST['id'];
            $result = $db->delete($id);

            $response['success'] = true;
            $response['message'] = 'Deleted Successfully';
            $response['data'] = $result;
            break;

        case 'restore':
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