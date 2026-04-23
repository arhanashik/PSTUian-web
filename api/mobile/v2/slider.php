<?php
require_once './db/slider_db.php';
require_once './constant.php';
 
$response = array();
$response['code'] = MISSING_PARAM;
$response['message'] = 'Required parameters are missing';
 
if (isset($_GET['call'])) 
{
    switch ($_GET['call']) 
    {
        case 'getAll':
            $db = new SliderDb();
            $data = $db->getAll();
            if(!$data) {
                $response['code'] = READ_FAILD;
                $response['message'] = 'No data found!';
            } else {
                $response['code'] = SUCCESS;
                $response['message'] = 'Total ' . count($data) . ' item(s)';
                $response['data'] = $data;
            }
            break;
		
        default:
            break;
    }
}

echo json_encode($response);