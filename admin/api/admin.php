<?php
require_once './validate_request.php';
require_once './db/admin_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new AdminDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            $response = $db->getAll();
            break;

        case 'add':
            if(!isset($_POST['email']) || empty($_POST['email'])
            || !isset($_POST['password']) || empty($_POST['password'])
            || !isset($_POST['role']) || empty($_POST['role'])) {
                break;
            }
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            if(strlen($password) < 6) {
                $response['message'] = 'Minmun password length is 6';
                break;
            }
            $result = $db->insert($email, $password, $role);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            if(!isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['email']) || empty($_POST['email'])
            || !isset($_POST['password']) || empty($_POST['password'])
            || !isset($_POST['role']) || empty($_POST['role'])) {
                break;
            }
            $id = $_POST['id'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            if(strlen($password) < 6) {
                $response['message'] = 'Minmun password length is 6';
                break;
            }
            $result = $db->update($id, $email, $password, $role);

            $response['success'] = true;
            $response['message'] = 'Updated Successfully';
            $response['data'] = $result;
            break;

        case 'changePassword':
            if(!isset($_POST['id']) || empty($_POST['id'])
            || !isset($_POST['old_password']) || empty($_POST['old_password'])
            || !isset($_POST['new_password']) || empty($_POST['new_password'])) {
                break;
            }
            $id = $_POST['id'];
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            if(strlen($old_password) < 6 || strlen($new_password) < 6) {
                $response['message'] = 'Minmun password length is 6';
                break;
            }
            $result = $db->updatePassword($id, $old_password, $new_password);

            if(!$result) {
                $response['message'] = 'Failed! Please check if old password is correct.';
                break;
            }
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

echo json_encode($response);