<?php
require_once './db/employee_db.php';
require_once './validate_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if ($validate && isset($_GET['call'])) 
{
    $db = new EmployeeDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            $response = $db->getAll();
            break;

        case 'add':
            $name = $_POST['name'];
            $designation = $_POST['designation'];
            $faculty_id = $_POST['faculty_id'];
            $department = $_POST['department'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $result = $db->insert($name, $designation, $faculty_id, $department, $address, $phone);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            $id = $_POST['id'];
            $name = $_POST['name'];
            $designation = $_POST['designation'];
            $faculty_id = $_POST['faculty_id'];
            $department = $_POST['department'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $result = $db->update($id, $name, $designation, $faculty_id, $department, $address, $phone);

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