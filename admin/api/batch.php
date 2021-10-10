<?php
require_once './validate_request.php';
require_once './db/batch_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new BatchDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            if(isset($_GET['faculty_id']) && !empty($_GET['faculty_id'])) {
                $faculty_id = $_GET['faculty_id'];
                $response = $db->getAllByFaculty($faculty_id);
                break;
            }
            $response = $db->getAll();
            break;

        case 'add':
            if(!isset($_POST['name']) || empty($_POST['name'])
            || !isset($_POST['title']) || empty($_POST['title'])
            || !isset($_POST['session']) || empty($_POST['session'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])
            || !isset($_POST['total_student']) || empty($_POST['total_student'])) {
                break;
            }
            $name = $_POST['name'];
            $title = $_POST['title'];
            $session = $_POST['session'];
            $faculty_id = $_POST['faculty_id'];
            $total_student = $_POST['total_student'];
            $result = $db->insert($name, $title, $session, $faculty_id, $total_student);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            if(!isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['name']) || empty($_POST['name'])
            || !isset($_POST['title']) || empty($_POST['title'])
            || !isset($_POST['session']) || empty($_POST['session'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])
            || !isset($_POST['total_student']) || empty($_POST['total_student'])) {
                break;
            }
            $id = $_POST['id'];
            $name = $_POST['name'];
            $title = $_POST['title'];
            $session = $_POST['session'];
            $faculty_id = $_POST['faculty_id'];
            $total_student = $_POST['total_student'];
            $result = $db->update($id, $name, $title, $session, $faculty_id, $total_student);

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