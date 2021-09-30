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

		$sql = "select * from course_schedule where faculty = '$faculty'";
		$result = mysqli_query($con, $sql);

		$num_of_res = mysqli_num_rows($result);
		
		if( $num_of_res > 0) {
			
			$response['success'] = true;
			$response['message'] = $num_of_res . ' result(s) found';
			
			$course_schedules = array();
			
			while($row = mysqli_fetch_array($result)) {
				$course_schedule = array();
				$course_schedule['id'] = $row['id'];
				$course_schedule['course_code'] = $row['course_code'];
				$course_schedule['course_title'] = $row['course_title'];
				$course_schedule['credit_hour'] = $row['credit_hour'];
				$course_schedule['faculty'] = $row['faculty'];
				$course_schedule['status'] = $row['status'];
			  
				array_push($course_schedules, $course_schedule);
			}
			
			$response['course_schedules'] = $course_schedules;
			
		}else {
			$response['message'] = 'No data found';
		}

		mysqli_close($con);
	}
}

echo json_encode($response);

?>