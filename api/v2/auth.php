<?php
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
            || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
            || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $email = $_POST['email'];
        $user_type = $_POST['user_type'];
        $device_id = $_POST['device_id'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = INVALID_PARAM;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;
        $user = $user_db->getByEmail($email);

        if(!$user) {
            $response['code'] = USER_NOT_FOUND;
            $response['message'] = 'Account does not exist!';
            break;
        }

        if($user['auth_user_id'] === '0') { // legacy user. user_id needs to be updated before signing in
            $response['code'] = USER_ID_INVALID;
            $response['message'] = 'Invalid user id!';
            break;
        }

        $response['code'] = SUCCESS;
        $response['messadage'] = 'Signed in Successfullly!';

        break;

    case 'signUpStudent':
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['reg']) || strlen($_POST['reg']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0
        || !isset($_POST['batch_id']) || strlen($_POST['batch_id']) <= 0
        || !isset($_POST['session']) || strlen($_POST['session']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $name = $_POST['name'];
        $id = $_POST['id'];
        $reg = $_POST['reg'];
        $faculty_id = $_POST['faculty_id'];
        $batch_id = $_POST['batch_id'];
        $session = $_POST['session'];
        $email = $_POST['email'];
        $device_id = $_POST['device_id'];
        $user_type = 'student';

        if($studentDb->isAlreadyInsered($id)) {
            $response['code'] = USER_ALREADY_EXIST;
            $response['message'] = 'Account already exists for this id!';
            break;
        }
        if($studentDb->isAlreadyInseredByEmail($email) || $teacherDb->isAlreadyInseredByEmail($email)) {
            $response['code'] = USER_ALREADY_EXIST;
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        $result = $studentDb->insert($name, $id, $reg, $faculty_id, $batch_id, $session, $email);
        if(!$result) {
            $response['code'] = USER_REGISTRATION_FAILED;
            $response['message'] = 'Failed to store information!';
            break;
        }
        $user = $studentDb->getByEmail($email);
        
        $response['code'] = SUCCESS;
        $response['message'] = 'Sign up Successful!';
        break;

    case 'signUpTeacher':
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0
        || !isset($_POST['designation']) || strlen($_POST['designation']) <= 0
        || !isset($_POST['department']) || strlen($_POST['department']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $name = $_POST['name'];
        $faculty_id = $_POST['faculty_id'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $device_id = $_POST['device_id'];
        $user_type = 'teacher';

        if($studentDb->isAlreadyInseredByEmail($email) || $teacherDb->isAlreadyInseredByEmail($email)) {
            $response['code'] = USER_ALREADY_EXIST;
            $response['message'] = 'Account already exists!';
            break;
        }
        $result = $teacherDb->insert($name, $faculty_id, $designation, $department, $email);
        if(!$result) {
            $response['code'] = USER_REGISTRATION_FAILED;
            $response['message'] = 'Failed to store information!';
            break;
        }
        $user = $teacherDb->getByEmail($email);
        
        $response['code'] = SUCCESS;
        $response['message'] = 'Sign up Successful!';
        break;

    case 'updateAuthUserId':
        require_once './auth_validator.php';
        $auth_user_id = FirebaseAuthValidator::uid();
        $auth_email = FirebaseAuthValidator::uid();

        if(!isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0) break;
        
        $user_type = $_POST['user_type'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = INVALID_PARAM;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;
        $result = $user_db->update_auth_user_id($auth_user_id, $auth_email);
        if(!$result) {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Failed to upate!';
            break;
        }

        $response['code'] = SUCCESS;
        $response['message'] = 'Updated successfully';

        break;

    case 'deleteAccount':
        require_once './auth_validator.php';
        $auth_email = FirebaseAuthValidator::email();

        if(!isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0) break;

        $user_type = $_POST['user_type'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = INVALID_PARAM;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;

        if(!$user_db->delete_account($auth_email)) {
            $response['code'] = WRITE_FAILED;
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