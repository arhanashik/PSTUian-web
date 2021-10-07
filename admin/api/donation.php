<?php
require_once './validate_request.php';
require_once './db/donation_db.php';
 
$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    $db = new DonationDb();
    switch ($_GET['call']) 
    {
        case 'getAll':
            $response = $db->getAll();
            break;

        case 'add':
            $name = $_POST['name'];
            $info = $_POST['info'];
            $email = $_POST['email'];
            $reference = $_POST['reference'];
            $result = $db->insert($name, $info, $email, $reference);

            $response['success'] = true;
            $response['message'] = 'Inserted Successfully';
            $response['data'] = $result;
            break;

        case 'update':
            $id = $_POST['id'];
            $name = $_POST['name'];
            $info = $_POST['info'];
            $email = $_POST['email'];
            $reference = $_POST['reference'];
            $result = $db->update($id, $name, $info, $email, $reference);

            $response['success'] = true;
            $response['message'] = 'Updated Successfully';
            $response['data'] = $result;
            break;

        case 'confirm':
            $id = $_POST['id'];
            $result = $db->updateConfirmation($id, 1);

            $response['success'] = true;
            $response['message'] = 'Updated Successfully';
            $response['data'] = $result;
            break;

        case 'unconfirm':
            $id = $_POST['id'];
            $result = $db->updateConfirmation($id, 0);

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