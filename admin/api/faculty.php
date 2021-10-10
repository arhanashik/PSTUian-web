<?php
require_once './validate_request.php';
require_once './db/faculty_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    switch ($_GET['call']) 
    {
        case 'getAll':
            $db = new FacultyDb();
            $response = $db->getAll();
            break;

        case 'add':
            if(!isset($_POST['short_title']) || empty($_POST['short_title'])
            || !isset($_POST['title']) || empty($_POST['title'])) {
                break;
            }
            $short_title = $_POST['short_title'];
            $title = $_POST['title'];
            $db = new FacultyDb();
            $result = $db->insert($short_title, $title);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            if(!isset($_POST['id']) || empty($_POST['id']) 
            || !isset($_POST['short_title']) || empty($_POST['short_title'])
            || !isset($_POST['title']) || empty($_POST['title'])) {
                break;
            }
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
            if(!isset($_POST['id']) || empty($_POST['id'])) {
                break;
            }
            $id = $_POST['id'];
            $db = new FacultyDb();
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