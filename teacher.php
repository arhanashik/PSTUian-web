<?php

$response['success'] = false;
$response['message'] = 'Required field(s) missing';

if($_SERVER['REQUEST_METHOD']=='GET'){

	if(!empty($_GET['faculty']))
	{
		$faculty = $_GET['faculty'];
		
		require "db_connect.php";

		$sql = "select * from teacher where faculty='$faculty'";
		$result = mysqli_query($con, $sql);

		$num_of_res = mysqli_num_rows($result);
		if( $num_of_res > 0) {
			$response['success'] = true;
			$response['message'] = $num_of_res . ' result(s) found';
			
			$teachers = array();
			while($row = mysqli_fetch_array($result))
			{
				$teacher = array();
				
				$teacher['id'] = $row['id'];
				$teacher['name'] = $row['name'];
				$teacher['designation'] = $row['designation'];
				$teacher['status'] = $row['status'];
				$teacher['phone'] = $row['phone'];
				$teacher['linked_in'] = $row['linked_in'];
				$teacher['address'] = $row['address'];
				$teacher['email'] = $row['email'];
				$teacher['department'] = $row['department'];
				$teacher['faculty'] = $row['faculty'];
				$teacher['fb_link'] = $row['fb_link'];
				$teacher['image_url'] = $row['image_url'];
			  
				array_push($teachers, $teacher);
			}
			
			$response['teachers'] = $teachers;
		}  else {
			$response['message'] = 'No data found';
		}

		mysqli_close($con);
	}
}

echo json_encode($response);

?>