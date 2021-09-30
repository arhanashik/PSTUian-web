<?php

$response = array();
$response['success'] = false;
$response['message'] = 'Invalid request';

if($_SERVER['REQUEST_METHOD']=='GET') {
	
	require "db_connect.php";

	$sql = "select * from faculty";
	$result = mysqli_query($con, $sql);

	$num_of_res = mysqli_num_rows($result);
	
	if( $num_of_res > 0) {
		
		$response['success'] = true;
		$response['message'] = $num_of_res . ' result(s) found';
		
		$faculties = array();
		while($row = mysqli_fetch_array($result))
		{
			$faculty = array();
			$faculty['id'] = $row['id'];
			$faculty['short_title'] = $row['short_title'];
			$faculty['title'] = $row['title'];
		  
			array_push($faculties, $faculty);
		}
		
		$response['faculties'] = $faculties;
		
	}else {
		$response['message'] = 'No data found';
	}

	mysqli_close($con);	
}

echo json_encode($response);

?>