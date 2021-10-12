<?php

class CommonRequest {
    
    // returns response if the request is handled.
    // returns false if the request is not handled.
    function handle($call, $db, $response) {
        switch ($call) {
            case 'getAll':
                //if there is any query parameters except call,
                // it should not be handled by CommonRequest
                if(!empty(array_diff($_GET, [$_GET['call']]))) {
                    return false;
                }
                return $db->getAll();

            case 'delete':
                if(!isset($_POST['id']) || empty($_POST['id'])) {
                    return $response;
                }
                $id = $_POST['id'];
                $result = $db->delete($id);
    
                $response['success'] = true;
                $response['message'] = 'Deleted Successfully';
                $response['data'] = $result;
                return $response;
    
            case 'restore':
                if(!isset($_POST['id']) || empty($_POST['id'])) {
                    return $response;
                }
                $id = $_POST['id'];
                $result = $db->restore($id);
    
                $response['success'] = true;
                $response['message'] = 'Restored Successfully';
                $response['data'] = $result;
                return $response;
    
            case 'deletePermanent':
                if(!isset($_POST['id']) || empty($_POST['id'])) {
                    return $response;
                }
                $id = $_POST['id'];
                $result = $db->deletePermanent($id);
    
                $response['success'] = true;
                $response['message'] = 'Deleted Successfully';
                $response['data'] = $result;
                return $response;
                
            default:
                return false;
        }
    }
}