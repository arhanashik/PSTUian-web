<?php
session_start();
require_once './constant.php';
require_once './db/auth_db.php';
require_once './db/student_db.php';
require_once './db/teacher_db.php';
require_once './util/util.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new AuthDb();
$studentDb = new StudentDb();
$teacherDb = new TeacherDb();
$util = new Util();

switch ($_GET['call']) {
    case 'signIn':
        if (!isset($_POST['email']) || strlen($_POST['email']) <= 0
            || !isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
            || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
            || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $email = $_POST['email'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $device_id = $_POST['device_id'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = INVALID_PARAM;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;
        if(!($id = $user_db->validate($user_id, $email))) {
            $response['code'] = USER_NOT_FOUND;
            $response['message'] = 'Invaild Account!';
            break;
        }
        
        if(!($user = $user_db->get($id))) {
            $response['code'] = USER_NOT_FOUND;
            $response['message'] = 'Invaild Account!';
            break;
        }
        unset($user['password']);

        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($user_id.$email, $time_now);
        $old_auth = $db->getByUserIdTypeAndDevice($user_id, $user_type, $device_id);
        if(!$old_auth || empty($old_auth)) {
            $result = $db->insert($user_id, $user_type, $auth_token, $device_id);
        } else {
            $result = $db->update($user_id, $user_type, $auth_token, $device_id);
        }
        if(!$result) {
            $response['code'] = AUTH_FAILED;
            $response['message'] = 'Failed to authenticate!';
            break;
        }

        $response['code'] = SUCCESS;
        $response['message'] = 'Signed in Successfullly!';
        $response['data'] = $user;
        $response['auth_token'] = $auth_token;

        break;

    case 'signUpStudent':
        if(!isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['reg']) || strlen($_POST['reg']) <= 0
        || !isset($_POST['batch_id']) || strlen($_POST['batch_id']) <= 0
        || !isset($_POST['session']) || strlen($_POST['session']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $batch_id = $_POST['batch_id'];
        $session = $_POST['session'];
        $email = $_POST['email'];
        $faculty_id = $_POST['faculty_id'];
        $device_id = $_POST['device_id'];
        $user_type = 'student';

        if($studentDb->isAlreadyInsered($id)) {
            $response['code'] = USER_ALREADY_EXIST;
            $response['message'] = 'Account already exists for this id!';
            break;
        }
        if($studentDb->isAlreadyInseredByEmail($email)) {
            $response['code'] = USER_ALREADY_EXIST;
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        $result = $studentDb->insert($user_id, $name, $id, $reg, $email, $batch_id, $session, $faculty_id);
        if(!$result) {
            $response['code'] = USER_REGISTRATION_FAILED;
            $response['message'] = 'Failed to store information!';
            break;
        }
        $user = $studentDb->get($id);
        unset($user['password']);
        
        $response['code'] = SUCCESS;
        $response['message'] = 'Sign up Successful!';
        $response['data'] = $user;
        break;

    case 'signUpTeacher':
        if(!isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['designation']) || strlen($_POST['designation']) <= 0
        || !isset($_POST['department']) || strlen($_POST['department']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $faculty_id = $_POST['faculty_id'];
        $device_id = $_POST['device_id'];
        $user_type = 'teacher';

        if($teacherDb->isAlreadyInseredByEmail($email)) {
            $response['code'] = USER_ALREADY_EXIST;
            $response['message'] = 'Account already exists!';
            break;
        }
        $result = $teacherDb->insert($$user_id, $name, $designation, $department, $email, $faculty_id);
        if(!$result) {
            $response['code'] = USER_REGISTRATION_FAILED;
            $response['message'] = 'Failed to store information!';
            break;
        }
        $user = $teacherDb->getByEmail($email);
        unset($user['password']);
        
        $response['code'] = SUCCESS;
        $response['message'] = 'Sign up Successful!';
        $response['data'] = $user;
        break;

    case 'signOut':
        if(!isset($_POST['id']) ||  strlen($_POST['id']) <= 0 
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) break;
        
        $id = $_POST['id'];
        $user_type = $_POST['user_type'];
        $device_id = $_POST['device_id'];
        $invalidateAuth = $db->invalidateAuth($id, $user_type, $device_id);
        if(!$invalidateAuth) {
            $response['code'] = AUTH_FAILED;
            $response['message'] = 'Failed to sign out!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Signed out successfullly';

        break;

    case 'signOutFromAllDevice':
        if(!isset($_POST['id']) ||  strlen($_POST['id']) <= 0 
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0) break;
        
        $id = $_POST['id'];
        $user_type = $_POST['user_type'];
        $result = $db->invalidateAllAuth($id, $user_type);
        if(!$result) {
            $response['code'] = AUTH_FAILED;
            $response['message'] = 'Failed to sign out!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Signed out successfullly from ' . $result . ' device(s)';

        break;

    case 'deleteAccount':
        if(!isset($_POST['user_id']) ||  strlen($_POST['user_id']) <= 0 
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0) break;

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $email = $_POST['email'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = INVALID_PARAM;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;
        if(!$user_db->get($user_id)) {    
            $response['code'] = USER_NOT_FOUND;
            $response['message'] = 'Account not found!';
            break;
        }
        
        $invalidateAllAuth = $db->invalidateAllAuth($user_id, $user_type);
        if(!$invalidateAllAuth) {
            $response['code'] = AUTH_FAILED;
            $response['message'] = 'Failed to complete the action!';
            break;
        }

        if(!$user_db->delete_account($user_id, $email)) {
            $response['code'] = WRITE_FAILD;
            $response['message'] = 'Account deletion failed. Please try again.';
            break;
        }

        $response['code'] = SUCCESS;
        $response['message'] = 'Request accepted successfullly. You account will no longer be available. To recover the account, please contact within 30 days before it is permanently deleted!';

        break;

    default:
        break;
}
 
echo json_encode($response);