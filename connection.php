<?php
	$host = "localhost";
	$user = "root";
	$password = "root";
	$database = "ProjectDemo1";
	
	$con = mysqli_connect($host, $user, $password, $database);
	
	if(mysqli_connect_errno()){
		die("Failed to connect with database, Error Code:" . mysqli_connect_error());
	}
	
?>
