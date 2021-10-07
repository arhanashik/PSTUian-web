<?php
require_once './db/faculty_db.php';
require_once './validate_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if ($validate && isset($_GET['call'])) 
{
    switch ($_GET['call']) 
    {
        case 'getAll':
            $db = new FacultyDb();
            $response = $db->getAll();
            break;

        case 'add':
            $short_title = $_POST['short_title'];
            $title = $_POST['title'];
            $db = new FacultyDb();
            $result = $db->insert($short_title, $title);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            $id = $_POST['id'];
            $short_title = $_POST['short_title'];
            $title = $_POST['title'];
            $db = new FacultyDb();
            $result = $db->update($id, $short_title, $title);

            $response['success'] = true;
            $response['message'] = 'Updated Successfully';
            $response['data'] = $result;
            break;

        case 'delete':
            $id = $_POST['id'];
            $db = new FacultyDb();
            $result = $db->delete($id);

            $response['success'] = true;
            $response['message'] = 'Deleted Successfully';
            $response['data'] = $result;
            break;

        case 'restore':
            $id = $_POST['id'];
            $db = new FacultyDb();
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