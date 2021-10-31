<?php
require_once './constant.php';
require_once './db/auth_db.php';
require_once './db/student_db.php';
require_once './db/teacher_db.php';
require_once './util/util.php';
 
$response = array();
$response['success'] = false;
$response['code'] = ERROR_REQUIRED_PARAMETES_MISSING;
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
            || !isset($_POST['password']) || strlen($_POST['password']) <= 0
            || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
            || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
                break;
            }
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $user_type = $_POST['user_type'];
        $device_id = $_POST['device_id'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;

        if(!($id = $user_db->validate($email, $password))) {
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Invaild Account!';
            break;
        }
        
        if(!($user = $user_db->get($id))) {
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Invaild Account!';
            break;
        }
        unset($user['password']);

        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($email.$user_type, $time_now);
        $old_auth = $db->getByUserIdAndType($user['id'], $user_type);
        if(empty($old_auth)) {
            $result = $db->insert($user['id'], $user_type, $auth_token, $device_id);
        } else {
            $result = $db->update($user['id'], $user_type, $auth_token, $device_id);
        }
        if($result) {
            $response['success'] = true;
            $response['code'] = SUCCESS;
            $response['message'] = 'Signed in Successfullly!';
            $response['data'] = $user;
            $response['auth_token'] = $auth_token;
        } else {
            $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
            $response['message'] = 'Failed to authenticate!';
        }

        break;

    case 'signUpStudent':
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['reg']) || strlen($_POST['reg']) <= 0
        || !isset($_POST['batch_id']) || strlen($_POST['batch_id']) <= 0
        || !isset($_POST['session']) || strlen($_POST['session']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
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
            $response['message'] = 'Account already exists!';
            break;
        }
        if($db->getByEmail($email)) {
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        //default password
        $password = md5($id);
        $result = $studentDb->insert($name, $id, $reg, $email, $batch_id, $session, $faculty_id, $password);
        if(!$result) {
            $response['code'] = ERROR_FAILED_TO_REGISTER;
            $response['message'] = 'Failed to store information!';
            break;
        } 
        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($email.$user_type, $time_now);
        $result = $db->insert($id, $user_type, $auth_token, $device_id);
        $user = $studentDb->get($id);
        unset($user['password']);
        
        $response['success'] = true;
        $response['message'] = 'Sign up Successful!';
        $response['data'] = $user;
        $response['auth_token'] = $auth_token;
        break;

    case 'signUpTeacher':
        if(!isset($_POST['name']) || strlen($_POST['name']) <= 0
        || !isset($_POST['designation']) || strlen($_POST['designation']) <= 0
        || !isset($_POST['department']) || strlen($_POST['department']) <= 0
        || !isset($_POST['email']) || strlen($_POST['email']) <= 0
        || !isset($_POST['password']) || strlen($_POST['password']) <= 0
        || !isset($_POST['faculty_id']) || strlen($_POST['faculty_id']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) {
            break;
        }
        $name = $_POST['name'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $faculty_id = $_POST['faculty_id'];
        $device_id = $_POST['device_id'];
        $user_type = 'teacher';
        if($teacherDb->isAlreadyInseredByEmail($email)) {
            $response['message'] = 'Account already exists!';
            break;
        }
        $result = $teacherDb->insert($name, $designation, $department, $email, $password, $faculty_id);
        if(!$result) {
            $response['code'] = ERROR_FAILED_TO_REGISTER;
            $response['message'] = 'Failed to store information!';
            break;
        } 
        $user = $teacherDb->getByEmail($email);
        unset($user['password']);

        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($email.$user_type, $time_now);
        $result = $db->insert($user['id'], $user_type, $auth_token, $device_id);
        
        $response['success'] = true;
        $response['message'] = 'Sign up Successful!';
        $response['data'] = $user;
        $response['auth_token'] = $auth_token;
        break;

    case 'signOut':
        if(!isset($_POST['id']) ||  strlen($_POST['id']) <= 0 
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0)
        {
            break;
        }
        $id = $_POST['id'];
        $user_type = $_POST['user_type'];
        $invalidateAuth = $db->invalidateAuth($id, $user_type);
        if(!$invalidateAuth) {
            $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
            $response['message'] = 'Failed to signed out!';
            break;
        }
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Signed out successfullly';

        break;

    case 'changePassword':
        require_once './auth_validation.php';
        if(!isset($_POST['user_id']) ||  strlen($_POST['user_id']) <= 0 
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['old_password']) || strlen($_POST['old_password']) <= 0
        || !isset($_POST['new_password']) || strlen($_POST['new_password']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) break;

        $id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $old_password = md5($_POST['old_password']);
        $new_password = md5($_POST['new_password']);
        $device_id = $_POST['device_id'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;

        if(!$user_db->get($id)) {    
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Account not found!';
            break;
        }

        if(!$user_db->validateByIdAndPassword($id, $old_password)) {   
            $response['code'] = ERROR_FAILED_TO_UPDATE; 
            $response['message'] = 'Wrong old password!';
            break;
        }

        if(!$user_db->update_password($id, $old_password, $new_password)) {
            $response['code'] = ERROR_FAILED_TO_UPDATE;
            $response['message'] = 'Password change failed. Please try again.';
            break;
        }

        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($new_password.$user_type, $time_now);
        $db->update($id, $user_type, $auth_token, $device_id);
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Password changed successfullly';
        $response['auth_token'] = $auth_token;

        break;

    case 'forgotPassword':
        if(!isset($_POST['email']) ||  strlen($_POST['email']) <= 0)
        {
            break;
        }
        $email = $_POST['email'];
        $student = $studentDb->getByEmail($email);
        if($student == null || !$student) {
            $response['message'] = 'Ops, no account for this email';
            break;
        }
        if(!$util->isValidEmail($email)) {
            $response['message'] = 'Ops, your email is not valid. So password reset by email 
            is not possible. Please contant with admin for password reset from help/support
            option.';
            break;
        }

        if(!$util->sendPasswordResetEmail($email)) {
            $response['message'] = 'Sorry, failed to send password reset email. Please try again.';
            break;
        }
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Password reset email sent successfullly. 
        Please check spam folder if you cannot find it.';

        break;
}
 
echo json_encode($response);