<?php
require_once './constant.php';
require_once './db/auth_db.php';
require_once './db/student_db.php';
require_once './util/util.php';
 
$response = array();
$response['success'] = false;
$response['code'] = ERROR_REQUIRED_PARAMETES_MISSING;
$response['message'] = 'Required parameters are missing';
 
if (isset($_GET['call'])) {
    $db = new AuthDb();
    $studentDb = new StudentDb();
    $util = new Util();
    switch ($_GET['call']) {
        case 'signIn':
            if (isset($_POST['email']) && strlen($_POST['email']) > 0 
                && isset($_POST['password']) && strlen($_POST['password']) > 0
                && isset($_POST['user_type']) && strlen($_POST['user_type']) > 0)
            {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $user_type = $_POST['user_type'];

                if($user_type === 'student') {
                    $user = $studentDb->validate($email, md5($password));
                } else {
                    $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
                    $response['message'] = 'Invaild Account!';
                    break;
                }
                
                if(!$user) {
                    $response['code'] = ERROR_ACCOUNT_DOES_NOT_EXIST;
                    $response['message'] = 'Invaild Account!';
                } else {
                    $time_now = date('Y-m-d H:i:s');
                    $auth_token = $util->getHash($email.$user_type, $time_now);
                    $old_auth = $db->getByUserIdAndType($user['id'], $user_type);
                    if(empty($old_auth)) {
                        $result = $db->insert($user['id'], $user_type, $auth_token);
                    } else {
                        $result = $db->update($user['id'], $user_type, $auth_token);
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
                }
            }
    
            break;

        case 'signUp':
            if(!isset($_POST['name']) || empty($_POST['name'])
            || !isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['reg']) || empty($_POST['reg'])
            || !isset($_POST['batch_id']) || empty($_POST['batch_id'])
            || !isset($_POST['session']) || empty($_POST['session'])
            || !isset($_POST['faculty_id']) || empty($_POST['faculty_id'])) {
                break;
            }
            $name = $_POST['name'];
            $id = $_POST['id'];
            $reg = $_POST['reg'];
            $batch_id = $_POST['batch_id'];
            $session = $_POST['session'];
            $faculty_id = $_POST['faculty_id'];
            $user_type = 'student';
            if($studentDb->isAlreadyInsered($id)) {
                $response['message'] = 'Account already exists!';
                break;
            }
            //default email and password
            $email = $id;
            $password = md5($reg);
            $result = $studentDb->insert($name, $id, $reg, $email, $batch_id, $session, $faculty_id, $password);
            if(!$result) {
                $response['code'] = ERROR_FAILED_TO_REGISTER;
                $response['message'] = 'Failed to store information!';
                break;
            } 
            $time_now = date('Y-m-d H:i:s');
            $auth_token = $util->getHash($email.$user_type, $time_now);
            $result = $db->insert($id, $user_type, $auth_token);
            $user = $studentDb->get($id);
            unset($user['password']);
            
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
    }
}
 
echo json_encode($response);