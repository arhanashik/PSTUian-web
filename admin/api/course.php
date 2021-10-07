<?php
require_once './db/course_db.php';
require_once './validate_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if ($validate && isset($_GET['call'])) 
{
    $db = new CourseDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            $response = $db->getAll();
            break;

        case 'add':
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