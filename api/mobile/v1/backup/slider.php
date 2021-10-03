<?php

$response = array();
$response['success'] = false;
$response['message'] = 'Invalid request';

if($_SERVER['REQUEST_METHOD']=='GET'){

	require "db_connect.php";

	$sql = "select * from slider";
	$result = mysqli_query($con, $sql);

	$num_of_res = mysqli_num_rows($result);
	
	if( $num_of_res > 0) {
		
		$response['success'] = true;
		$response['message'] = $num_of_res . ' result(s) found';
		
		$sliders = array();
		
		while($row = mysqli_fetch_array($result)) {
			$slider = array();
			$slider['id'] = $row['id'];
			$slider['title'] = $row['title'];
			$slider['image_url'] = $row['image_url'];
		  
			array_push($sliders, $slider);
		}
		
		$response['data'] = $sliders;
		
	}else {
		$response['message'] = 'No data found';
	}

	mysqli_close($con);
}

echo json_encode($response);

?>