<?php

$response = array();
$response['success'] = false;
$response['message'] = 'Invalid request';

if(empty($_GET['call'])) {
	$response['message'] = 'Required field(s) missing';
	
}else {
	
	$call = $_GET['call'];

	require "db_connect.php";
	
	switch($call) {
		case 'option':
			$sql = "select donation_option from info";
			$result = mysqli_query($con, $sql);
			if(mysqli_num_rows($result) > 0) {
				$response['success'] = true;
				$response['message'] = 'Donation options found';
				
				while($row = mysqli_fetch_array($result)) {
					$response['donation_option'] = $row['donation_option'];
					break;
				}
			}else {
				$response['message'] = 'No data found';
			}
			break;
		
		case 'save':
			if(empty($_POST['name']) || empty($_POST['info']) 
				|| empty($_POST['email']) || empty($_POST['reference'])) {
				$response['message'] = 'Required field(s) missing';
				
			}else {
				$name = $_POST['name'];
				$info = $_POST['info'];
				$email = $_POST['email'];
				$reference = $_POST['reference'];
				$sql = "INSERT INTO donation(name, info, email, reference, confirm) VALUES('$name', '$info', '$email', '$reference', 0)";
				
				if(mysqli_query($con, $sql)) {
					$response['success'] = true;
					$response['message'] = 'Donation is under review! Thanks for your help.';
				}else {
					$response['message'] = 'Ops, somethings wrong. Please try again.';
				}
			}
			
			break;
			
		case 'donors':
			$sql = "select * from donation where confirm = 1";
			$result = mysqli_query($con, $sql);
			
			$num_of_res = mysqli_num_rows($result);
		
			if( $num_of_res > 0) {
				
				$response['success'] = true;
				$response['message'] = $num_of_res . ' result(s) found';
				
				$donors = array();
				while($row = mysqli_fetch_array($result)) {
					$donor = array();
					$donor['id'] = $row['id'];
					$donor['name'] = $row['name'];
					$donor['info'] = $row['info'];
					$donor['email'] = $row['email'];
					$donor['reference'] = $row['reference'];
				  
					array_push($donors, $donor);
				}
				
				$response['donors'] = $donors;
			}else {
				$response['message'] = 'No data found';
			}
			break;
	}

	mysqli_close($con);
}

echo json_encode($response);

?>