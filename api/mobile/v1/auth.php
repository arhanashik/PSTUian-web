<?php
session_start();
require_once './constant.php';
require_once './db/auth_db.php';
require_once './db/student_db.php';
require_once './db/teacher_db.php';
require_once './db/password_reset_db.php';
require_once './db/verification_db.php';
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
$passwordResetDb = new PasswordResetDb();
$verificationDb = new VerificationDb();
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

        // check account verification entry
        $verification = $verificationDb->getByUser($id, $user_type);
        if(!$verification || $verification['email_verification'] !== 1) {
            $response['code'] = ERROR_EMAIL_NOT_VERIFIED;
            $response['message'] = 'Your email is not verified yet! Please complete your email verification and try again.';
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
        $old_auth = $db->getByUserIdTypeAndDevice($user['id'], $user_type, $device_id);
        if(!$old_auth || empty($old_auth)) {
            $result = $db->insert($user['id'], $user_type, $auth_token, $device_id);
        } else {
            $result = $db->update($user['id'], $user_type, $auth_token, $device_id);
        }
        if(!$result) {
            $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
            $response['message'] = 'Failed to authenticate!';
            break;
        }

        $_SESSION['x_auth_token'] = $auth_token;
        $_SESSION['x_user_type'] = $user_type;
        $_SESSION['x_user'] = $user;
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Signed in Successfullly!';
        $response['data'] = $user;
        $response['auth_token'] = $auth_token;

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
            $response['message'] = 'Account already exists for this id!';
            break;
        }
        if($studentDb->isAlreadyInseredByEmail($email)) {
            $response['message'] = 'Ops, Account already exists for this email';
            break;
        }
        // default password
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

        // create verification entry
        $verificationDb->insert($id, "student");
        // send verification email
        $verification_link = BASE_EMAIL_VERIFICATION_URL . "&ui=" . $user['id'];
        $verification_link .= "&ut=" . $user_type . "&at=" . $auth_token . "&di=" . $device_id;
        $util->sendVerificationEmail($email, $verification_link);
        
        $response['success'] = true;
        $response['message'] = 'Sign up Successful! A verification link has been sent to your email address. Please check your inbox.';
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

        // create verification entry
        $verificationDb->insert($user['id'], "teacher");
        // send verification email
        $verification_link = BASE_EMAIL_VERIFICATION_URL . "&ui=" . $user['id'];
        $verification_link .= "&ut=" . $user_type . "&at=" . $auth_token . "&di=" . $device_id;
        $util->sendVerificationEmail($email, $verification_link);
        
        $response['success'] = true;
        $response['message'] = 'Sign up Successful! A verification link has been sent to your email address. Please check your inbox.';
        $response['data'] = $user;
        $response['auth_token'] = $auth_token;
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
            $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
            $response['message'] = 'Failed to sign out!';
            break;
        }
        session_destroy();
        $response['success'] = true;
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
            $response['code'] = ERROR_FAILED_TO_AUTHENTICATE;
            $response['message'] = 'Failed to sign out!';
            break;
        }
        session_destroy();
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Signed out successfullly from ' . $result . ' device(s)';

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

        // update auth token
        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($new_password.$user_type, $time_now);
        $db->update($id, $user_type, $auth_token, $device_id);

        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Password changed successfullly';
        $response['auth_token'] = $auth_token;

        break;

    case 'forgotPassword':
        if(!isset($_POST['user_type']) ||  strlen($_POST['user_type']) <= 0
        || !isset($_POST['email']) ||  strlen($_POST['email']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) break;

        $user_type = $_POST['user_type'];
        $email = $_POST['email'];
        $email = $_POST['email'];
        $device_id = $_POST['device_id'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;

        $user = $user_db->getByEmail($email);
        if($user == null || !$user) {
            $response['message'] = 'Ops, no account for this email';
            break;
        }

        if(!$util->isValidEmail($email)) {
            $response['message'] = 'Ops, your email is not valid. So password reset by email 
            is not possible. Please contant with admin for password reset from help/support
            option.';
            break;
        }

        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($email.$user_type, $time_now);
        if($passwordResetDb->getByUserIdAndType($user['id'], $user_type)) {
            $result = $passwordResetDb->update($user['id'], $user_type, $email, $auth_token);
        } else {
            $result = $passwordResetDb->insert($user['id'], $user_type, $email, $auth_token);
        }
        $reset_link = BASE_URL . "reset_password.php?ui=" . $user['id'] . "&ut=" . $user_type; 
        $reset_link .= "&at=" . $auth_token . "&di=" . $device_id;
        if(!$util->sendPasswordResetEmail($email, $reset_link)) {
            $response['message'] = 'Sorry, failed to send password reset email. Please try again.';
            break;
        }
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Password reset email sent successfullly. 
        Please check spam folder if you cannot find it.';

        break;

    case 'resetPassword':
        if(!isset($_POST['user_id']) ||  strlen($_POST['user_id']) <= 0 
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0
        || !isset($_POST['password']) || strlen($_POST['password']) <= 0) break;

        $id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        $password = md5($_POST['password']);
        $device_id = $_POST['device_id'];
        $auth_token = $_SERVER['HTTP_X_AUTH_TOKEN'];

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

        if(!$passwordResetDb->isValidToken($id, $user_type, $auth_token)) {   
            $response['code'] = ERROR_FAILED_TO_AUTHENTICATE; 
            $response['message'] = 'Invalid Request!';
            break;
        }

        if(!$user_db->reset_password($id, $password)) {
            $response['code'] = ERROR_FAILED_TO_UPDATE;
            $response['message'] = 'Password reset failed. Please try again.';
            break;
        }

        //invalidate password reset token
        $passwordResetDb->invalidateToken($id, $user_type);
        //update auth token
        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($password.$user_type, $time_now);
        $db->update($id, $user_type, $auth_token, $device_id);
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Password reset successfullly';
        $response['auth_token'] = $auth_token;

        break;

    case 'emailVarification':
        if(!isset($_GET['ui']) ||  strlen($_GET['ui']) <= 0 
        || !isset($_GET['ut']) || strlen($_GET['ut']) <= 0
        || !isset($_GET['at']) || strlen($_GET['at']) <= 0
        || !isset($_GET['di']) || strlen($_GET['di']) <= 0) {
            $response = 'Required parameters are missing!';
            break;
        }

        $id = $_GET['ui'];
        $user_type = $_GET['ut'];
        $auth_token = $_GET['at'];
        $device_id = $_GET['di'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;
        if(!$user_db->get($id)) {
            $response = 'Account not found!';
            break;
        }

        if(!$db->isValidTokenForUser($id, $user_type, $auth_token)) {
            $response = 'Invalid Request!' . $auth_token;
            break;
        }

        // get verification entry
        $verification = $verificationDb->getByUser($id, $user_type);
        if(!$verification) {
            $response = 'Invalid Request! No Data found.';
            break;
        }

        // check already verified
        if($verification['email_verification'] === 1) {
            $response = 'Your email is already verified!';
            break;
        }

        // update verification
        if(!$verificationDb->update($verification['id'], 1)) {
            $response = 'Email verification failed. Please try again.';
            break;
        }

        //update auth token
        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($auth_token, $time_now);
        $db->update($id, $user_type, $auth_token, $device_id);

        $response = 'Email Varification Successful. You can sign in now.';
        break;

    case 'resendVerificationEmail':
        if(!isset($_POST['user_type']) ||  strlen($_POST['user_type']) <= 0
        || !isset($_POST['email']) ||  strlen($_POST['email']) <= 0
        || !isset($_POST['device_id']) || strlen($_POST['device_id']) <= 0) break;

        $user_type = $_POST['user_type'];
        $email = $_POST['email'];
        $device_id = $_POST['device_id'];

        if(!($user_type === 'student' || $user_type === 'teacher')) {
            $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
            $response['message'] = 'Invaild User Type!';
            break;
        }

        $user_db = ($user_type === 'student')? $studentDb : $teacherDb;

        $user = $user_db->getByEmail($email);
        if($user == null || !$user) {
            $response['message'] = 'Ops, no account for this email';
            break;
        }

        if(!$util->isValidEmail($email)) {
            $response['message'] = 'Ops, your email is not valid. So email verification 
            is not possible. Please contact with admin from help/support option.';
            break;
        }

        // get verification entry
        $verification = $verificationDb->getByUser($user['id'], $user_type);

        // check already verified
        if($verification && $verification['email_verification'] === 1) {
            $response['message'] = 'Your email is already verified!';
            break;
        }
        
        // create verification entry if not exists
        if(!$verification) {
            $verificationDb->insert($user['id'], $user_type);
        }

        // update auth token
        $time_now = date('Y-m-d H:i:s');
        $auth_token = $util->getHash($email.$user_type, $time_now);
        $auth_updated = $db->update($user['id'], $user_type, $auth_token, $device_id);
        if(!$auth_updated) {
            $response['message'] = 'Authentication process failed. Please try again.';
            break;
        }

        // send verification email
        $verification_link = BASE_EMAIL_VERIFICATION_URL . "&ui=" . $user['id']; 
        $verification_link .= "&ut=" . $user_type . "&at=" . $auth_token . "&di=" . $device_id;
        if(!$util->sendVerificationEmail($email, $verification_link)) {
            $response['message'] = 'Sorry, failed to send verification email. Please try again.';
            break;
        }
        $response['success'] = true;
        $response['code'] = SUCCESS;
        $response['message'] = 'Verification email sent successfullly. Please check spam folder if you cannot find it.';

        break;

    default:
        break;
}
 
echo json_encode($response);