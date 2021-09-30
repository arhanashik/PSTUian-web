<?php

$response = array();
$response['success'] = false;
$response['message'] = 'Invalid request';

if($_SERVER['REQUEST_METHOD']=='GET'){

	if(empty($_GET['faculty'])) {
		$response['message'] = 'Required field(s) missing';
	}else {
		
		$faculty = $_GET['faculty'];

		require "db_connect.php";

		$sql = "select * from employee where faculty = '$faculty'";
		$result = mysqli_query($con, $sql);

		$num_of_res = mysqli_num_rows($result);
		
		if( $num_of_res > 0) {
			
			$response['success'] = true;
			$response['message'] = $num_of_res . ' result(s) found';
			
			$employees = array();
			
			while($row = mysqli_fetch_array($result)) {
				$employee = array();
				$employee['id'] = $row['id'];
				$employee['name'] = $row['name'];
				$employee['designation'] = $row['designation'];
				$employee['department'] = $row['department'];
				$employee['phone'] = $row['phone'];
				$employee['address'] = $row['address'];
				$employee['faculty'] = $row['faculty'];
				$employee['image_url'] = $row['image_url'];
			  
				array_push($employees, $employee);
			}
			
			$response['employees'] = $employees;
			
		}else {
			$response['message'] = 'No data found';
		}

		mysqli_close($con);
	}
}

echo json_encode($response);

?>