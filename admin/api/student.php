<?php
require_once './validate_request.php';
require_once './db/student_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new StudentDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            if(isset($_GET['faculty_id']) && isset($_GET['batch_id'])) {
                $faculty_id = $_GET['faculty_id'];
                $batch_id = $_GET['batch_id'];
                $response = $db->getAllByFacultyAndBatch($faculty_id, $batch_id);
                break;
            }
            if(isset($_GET['faculty_id'])) {
                $faculty_id = $_GET['faculty_id'];
                $response = $db->getAllByFaculty($faculty_id);
                break;
            }
            $response = $db->getAll();
            break;

        case 'add':
            if(!isset($_POST['name']) || empty($_POST['name'])
            || !isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['reg']) || empty($_POST['reg'])
            || !isset($_POST['batch_id']) || empty($_POST['batch_id'])
            || !isset($_POST['session']) || empty($_POST['session'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
                break;
            }
            $name = $_POST['name'];
            $id = $_POST['id'];
            $reg = $_POST['reg'];
            $batch_id = $_POST['batch_id'];
            $session = $_POST['session'];
            $faculty_id = $_POST['faculty_id'];
            $result = $db->insert($name, $id, $reg, $batch_id, $session, $faculty_id);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            if(!isset($_POST['name']) || empty($_POST['name'])
            || !isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['reg']) || empty($_POST['reg'])
            || !isset($_POST['batch_id']) || empty($_POST['batch_id'])
            || !isset($_POST['session']) || empty($_POST['session'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
                break;
            }
            $name = $_POST['name'];
            $id = $_POST['id'];
            $reg = $_POST['reg'];
            $batch_id = $_POST['batch_id'];
            $session = $_POST['session'];
            $faculty_id = $_POST['faculty_id'];
            $result = $db->update($name, $id, $reg, $batch_id, $session, $faculty_id);

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