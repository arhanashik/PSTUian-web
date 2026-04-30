<?php
require_once './auth_validator.php';
require_once './db/check_in_db.php';
require_once './db/check_in_location_db.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';

if(!isset($_GET['call']) || empty($_GET['call'])) {
    echo json_encode($response);
    return;
}

$call = $_GET['call'];
$db = new CheckInDb();
$checkInLocationDb = new CheckInLocationDb();
 
switch ($_GET['call']) 
{
    case 'getAll':
        $location_id = -1;
        $user_id = -1;
        $user_type = null;
        if(isset($_GET['location_id']) && strlen($_GET['location_id']) > 0) {
            $location_id = $_GET['location_id'];
        } else if(isset($_GET['user_id']) && strlen($_GET['user_id']) > 0 
            && isset($_GET['user_type']) && strlen($_GET['user_type']) > 0) {
            $user_id = $_GET['user_id'];
            $user_type = $_GET['user_type'];
        } else {
            // if location_id or (user_id and user_type) is not available in the request, return
            break;
        }

        $page = 1;
        $limit = 20;
        if(isset($_GET['page']) && strlen($_GET['page']) > 0) {
            $page = $_GET['page'];
        }
        if(isset($_GET['limit']) && strlen($_GET['limit']) > 0) {
            $limit = $_GET['limit'];
        }

        $data = false;
        if($location_id !== -1) {
            $data = $db->getAllByLocation($location_id, $page, $limit);
        } else if($user_id !== -1 && $user_type !== null) {
            $data = $db->getAllByUser($user_id, $user_type, $page, $limit);
        }

        if ($data === false) {
            $response['code'] = READ_FAILED;
            $response['message'] = 'Database error occurred.';
            return;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Total ' . count($data) . ' item(s)';
        $response['data'] = $data;
        break;

    case 'get':
        if(!isset($_GET['user_id']) || strlen($_GET['user_id']) <= 0
        || !isset($_GET['user_type']) || strlen($_GET['user_type']) <= 0) break;

        $user_id = $_GET['user_id'];
        $user_type = $_GET['user_type'];

        $data = $db->getByUser($user_id, $user_type);
        if($data === null || empty($data)) 
        {
            $response['code'] = READ_FAILED;
            $response['message'] = 'No data found!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Data found';
        $response['data'] = $data;
        break;

    case 'checkIn':
        if(!isset($_POST['location_id']) || strlen($_POST['location_id']) <= 0
        || !isset($_POST['user_id']) || strlen($_POST['user_id']) <= 0
        || !isset($_POST['user_type']) || strlen($_POST['user_type']) <= 0) break;

        $location_id = $_POST['location_id'];
        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];

        // check if valid location
        $location = $checkInLocationDb->get($location_id);
        if(!$location || empty($location)) {
            $response['code'] = INVALID_PARAM;
            $response['message'] = 'Invalid location!';
            break;
        }

        $oldCheckIn = $db->getByUser($user_id, $user_type);
        if(!$oldCheckIn || empty($oldCheckIn)) { 
            // new check in
            $check_in_id = $db->insert($location_id, $user_id, $user_type);
        } else {
            //checked in before, so increment the check in count
            $check_in_id = $oldCheckIn['id'];
            $result = $db->updateLocation($check_in_id, $location_id);
        }
        
        if(!$check_in_id || $check_in_id <= 0) {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Sorry, check in failed!';
            break;
        }

        // increment the check in count
        $checkInLocationDb->incrementCount($check_in_id);

        $response['code'] = SUCCESS;
        $response['message'] = 'Checked in successfullly!';
        $response['data'] = $db->getCheckIn($check_in_id);
        break;

    case 'privacy':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0
        || !isset($_POST['privacy']) || strlen($_POST['privacy']) <= 0) break;

        $id = $_POST['id'];
        $privacy = $_POST['privacy'];
        
        $result = $db->updatePrivacy($id, $privacy);
        if(!$result || $result <= 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Update failed!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Updated successfullly!';
        $response['data'] = $db->getCheckIn($id);
        break;

    case 'delete':
        if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) break;

        $id = $_POST['id'];
        
        $result = $db->delete($id);
        if(!$result || $result <= 0) 
        {
            $response['code'] = WRITE_FAILED;
            $response['message'] = 'Failed to delete!';
            break;
        }
        $response['code'] = SUCCESS;
        $response['message'] = 'Deleted successfullly!';
        $response['data'] = $id;
        break;
    
    default:
        break;
}

echo json_encode($response);