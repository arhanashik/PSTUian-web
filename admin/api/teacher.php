<?php
require_once './validate_request.php';
require_once './db/teacher_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new TeacherDb();
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
            || !isset($_POST['designation']) || empty($_POST['designation'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])
            || !isset($_POST['department']) || empty($_POST['department'])
            || !isset($_POST['address']) || !isset($_POST['phone'])
            || !isset($_POST['email'])) {
                break;
            }
            $name = $_POST['name'];
            $designation = $_POST['designation'];
            $faculty_id = $_POST['faculty_id'];
            $department = $_POST['department'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $result = $db->insert($name, $designation, $faculty_id, $department, $address, $phone, $email);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            if(!isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['name']) || empty($_POST['name'])
            || !isset($_POST['designation']) || empty($_POST['designation'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])
            || !isset($_POST['department']) || empty($_POST['department'])
            || !isset($_POST['address']) || !isset($_POST['phone'])
            || !isset($_POST['email'])) {
                break;
            }
            $id = $_POST['id'];
            $name = $_POST['name'];
            $designation = $_POST['designation'];
            $faculty_id = $_POST['faculty_id'];
            $department = $_POST['department'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $result = $db->update($id, $name, $designation, $faculty_id, $department, $address, $phone, $email);

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

// echo http_response_code(404);
echo json_encode($response);