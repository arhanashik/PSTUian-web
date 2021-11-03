<?php
require_once './auth_validation.php';
require_once './db/batch_db.php';
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
$db = new BatchDb();
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