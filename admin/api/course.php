<?php
require_once './validate_request.php';
require_once './db/course_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new CourseDb();
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
            if(!isset($_POST['course_code']) || empty($_POST['course_code'])
            || !isset($_POST['course_title']) || empty($_POST['course_title'])
            || !isset($_POST['credit_hour']) || empty($_POST['credit_hour'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
                break;
            }
            $course_code = $_POST['course_code'];
            $course_title = $_POST['course_title'];
            $credit_hour = $_POST['credit_hour'];
            $faculty_id = $_POST['faculty_id'];
            $result = $db->insert($course_code, $course_title, $credit_hour, $faculty_id);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            if(!isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['course_code']) || empty($_POST['course_code'])
            || !isset($_POST['course_title']) || empty($_POST['course_title'])
            || !isset($_POST['credit_hour']) || empty($_POST['credit_hour'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
                break;
            }
            $id = $_POST['id'];
            $course_code = $_POST['course_code'];
            $course_title = $_POST['course_title'];
            $credit_hour = $_POST['credit_hour'];
            $faculty_id = $_POST['faculty_id'];
            $result = $db->update($id, $course_code, $course_title, $credit_hour, $faculty_id);

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