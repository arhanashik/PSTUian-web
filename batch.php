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

		$sql = "select * from batch where faculty = '$faculty'";
		$result = mysqli_query($con, $sql);

		$num_of_res = mysqli_num_rows($result);
		
		if( $num_of_res > 0) {
			
			$response['success'] = true;
			$response['message'] = $num_of_res . ' result(s) found';
			
			$batches = array();
			
			while($row = mysqli_fetch_array($result)) {
				$batch = array();
				$batch['id'] = $row['id'];
				$batch['name'] = $row['name'];
				$batch['title'] = $row['title'];
				$batch['session'] = $row['session'];
				$batch['faculty'] = $row['faculty'];
				$batch['total_student'] = $row['total_student'];
			  
				array_push($batches, $batch);
			}
			
			$response['batches'] = $batches;
			
		}else {
			$response['message'] = 'No data found';
		}

		mysqli_close($con);
	}
}

echo json_encode($response);

?>