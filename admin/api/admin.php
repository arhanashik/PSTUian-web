<?php
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
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $result = $db->insert($email, $password, $role);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            $id = $_POST['id'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $result = $db->update($id, $email, $password, $role);

            $response['success'] = true;
            $response['message'] = 'Updated Successfully';
            $response['data'] = $result;
            break;

        case 'changePassword':
            $id = $_POST['id'];
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $result = $db->updatePassword($id, $old_password, $new_password);

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