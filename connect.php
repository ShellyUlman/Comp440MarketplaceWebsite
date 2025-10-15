<?php 

// Database connection

	//set connection 
	//$UserDBConnect = new mysqli("localhost", "root", "", "users");
	//$UserDBConnect = new mysqli("localhost", "root", "", "users");


	//check connection
	//if($UserDBConnect->connect_error){
		//die("SQL Connection Failed: " . $UserDBConnect->connect_error);
	//} 



// Database connection

	//set connection 
	$LogConnect = mysqli_connect("127.0.0.1", "root", "", "users") or die("Could not connect");

	//check connection
	if(mysqli_connect_errno()){
		die("SQL Connection Failed: " . $LogConnect->connect_error());
	} 

	if(!$LogConnect){
		die("connectin failed: " . my_sqli_connect_error());
	}





?>
