<?php
require_once './db/info_db.php';
require_once './db/donation_db.php';

$response = array();
$response['success'] = false;
$response['message'] = 'Required parameters are missing';

if (isset($_GET['call'])) 
{
    switch ($_GET['call']) 
    {
        case 'option':
            $db = new InfoDb();
            $data = $db->getDonationOption();
            if($data == null || $data == '') 
            {
                $response['message'] = 'No data found!';
            }
            else
            {
                $response['success'] = true;
                $response['message'] = 'Data found!';
                $response['data'] = $data;
            }
            break;

		case 'save':
            if($_POST['name'] === null || strlen($_POST['name']) <= 0
            || $_POST['email'] === null || strlen($_POST['email']) <= 0
            || $_POST['reference'] === null || strlen($_POST['reference']) <= 0
            || $_POST['info'] === null || strlen($_POST['info']) <= 0) {
				break;
			}
			$name = $_POST['name'];
			$info = $_POST['info'];
			$email = $_POST['email'];
			$reference = $_POST['reference'];

			$db = new DonationDb();
			$result = $db->insert($name, $info, $email, $reference);
			
			if($result === null || !$result) 
			{
				$response['message'] = "Ops, couldn't save donation info. Please try again.";
			}
			else 
			{
				$response['success'] = true;
				$response['message'] = 'Donation is under review! Thanks for your help.';
				$response['data'] = $result;
			}
			
			break;
		
		case 'donors':
			$db = new DonationDb();
            $data = $db->getAll();
            if(empty($data)) 
            {
                $response['message'] = 'No data found!';
            }
            else
            {
                $response['success'] = true;
                $response['message'] = 'Total ' . count($data) . ' item(s)';
                $response['data'] = $data;
            }
            break;
		
        default:
            break;
    }
}

echo json_encode($response);

?>