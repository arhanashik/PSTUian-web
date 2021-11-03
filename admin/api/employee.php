<?php
require_once './auth_validation.php';
require_once './db/employee_db.php';
require_once './db/log_db.php';
require_once './common_request.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new EmployeeDb();
$logDb = new LogDb();
$common_request = new CommonRequest();
$result = $common_request->handle($call, $db, $response);
if($result) {
    echo json_encode($result);
    return;
}

switch ($_GET['call']) 
{
    case 'getAll':
        if(isset($_GET['faculty_id']) && !empty($_GET['faculty_id'])) {
            $faculty_id = $_GET['faculty_id'];
            $response = $db->getAllByFaculty($faculty_id);
        }
        break;

    case 'add':
        if(!isset($_POST['name']) || empty($_POST['name'])
        || !isset($_POST['designation']) || empty($_POST['designation'])
        || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])
        || !isset($_POST['department']) || empty($_POST['department'])
        || !isset($_POST['address']) || !isset($_POST['phone'])) {
            break;
        }
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
        // add log
        $log_data = json_encode($db->getSingle($result));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'add', $log_data);
        }
        break;

    case 'update':
        if(!isset($_POST['id']) || empty($_POST['id'])
        || !isset($_POST['name']) || empty($_POST['name'])
        || !isset($_POST['designation']) || empty($_POST['designation'])
        || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])
        || !isset($_POST['department']) || empty($_POST['department'])
        || !isset($_POST['address']) || !isset($_POST['phone'])) {
            break;
        }
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
        // add log
        $log_data = json_encode($db->getSingle($id));
        if(isset($_SERVER['HTTP_X_ADMIN_ID'])) {
            $admin_id = $_SERVER['HTTP_X_ADMIN_ID'];
            $logDb->insert($admin_id, 'admin', 'update', $log_data);
        }
        break;
    
    default:
        break;
}

echo json_encode($response);