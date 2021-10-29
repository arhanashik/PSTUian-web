<?php
require_once './db/log_db.php';

class CommonRequest {
    
    // returns response if the request is handled.
    // returns false if the request is not handled.
    function handle($call, $db, $response) {
        $logDb = new LogDb();
        switch ($call) {
            case 'getAll':
                //if there is any query parameters except call,
                // it should not be handled by CommonRequest
                if(!empty(array_diff($_GET, [$_GET['call']]))) {
                    return false;
                }
                return $db->getAll();

            case 'get':
                if(!isset($_GET['id']) || strlen($_GET['id']) <= 0) {
                    return $response;
                }
                $id = $_GET['id'];
                return $db->get($id);

            case 'delete':
                if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
                    return $response;
                }
                $id = $_POST['id'];
                $result = $db->delete($id);
                if($result == null || !$result) {
                    $response['message'] = 'Operation failed!';
                    return $response;
                }
    
                $logDb->insert($id, 'admin', 'delete', $id);
                $response['success'] = true;
                $response['message'] = 'Deleted Successfully';
                $response['data'] = $result;
                return $response;
    
            case 'restore':
                if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
                    return $response;
                }
                $id = $_POST['id'];
                $result = $db->restore($id);
                if($result == null || !$result) {
                    $response['message'] = 'Operation failed!';
                    return $response;
                }
    
                $response['success'] = true;
                $response['message'] = 'Restored Successfully';
                $response['data'] = $result;
                return $response;
    
            case 'deletePermanent':
                if(!isset($_POST['id']) || strlen($_POST['id']) <= 0) {
                    return $response;
                }
                $id = $_POST['id'];
                $result = $db->deletePermanent($id);
                if($result == null || !$result) {
                    $response['message'] = 'Operation failed!';
                    return $response;
                }
    
                $response['success'] = true;
                $response['message'] = 'Deleted Successfully';
                $response['data'] = $result;
                return $response;
                
            default:
                return false;
        }
    }
}