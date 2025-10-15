
<!Doctype html>
<html>

<?php
include 'connect.php';
?>
<div class = "container" content = "width = device-width"> <!--//style = max-wdth 100%-->
		<div class = "text-center mt - 5 mb - 4">
			<h2><center> Login Page</center></h2>
		</div>
		

		<h2> Sign Up </h2>
		<form method = "post">
			Username: <input type="text" name = "signup_username" required><br>
			Password: <input type= "text" name = "signup_password" required><br>
			First Name: <input type= "text" name = "firstName" required><br>
			Last Name: <input type= "text" name = "lastName" required><br>
			Email: <input type= "text" name = "email" required><br>
			Phone: <input type= "text" name = "phone" required><br>
			<input type = "submit" name= "signUp" value = "Sign Up"> <br><br>
		</form>

		<h2> Log in </h2>
		<form method = "post">
			Username: <input type="text" name = "login_username" required><br>
			Password: <input type= "text" name = "login_password" required><br>
			<input type = "submit" name = "login" value = "Login">
		</form>
		
	</div>
</html>

<?php

//Sign Up

if (isset($_POST['signUp'])) {
	$username = $_POST['signup_username'];
	$password = $_POST['signup_password'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];

	$sql = "INSERT INTO Users (username, password, firstName, lastName, email, phone) VALUES ('$username', '$password', '$firstName', '$lastName', '$email', '$phone')";
	if ($UserDBConnect->query($sql) === TRUE) {
		echo "SignUp successful!";
	} else {
		echo "Error: " . $UserDBConnect->error;
	}
}


//Login
if (isset($_POST['login'])) {
	$username = $_POST['login_username'];
	$password = $_POST['login_password'];

	$sql = "SELECT * FROM Users WHERE username - '$username' AND password = '$password'";

	if ($UserDBConnect->query($sql)->num_rows > 0) {
		echo "Login successful! Welcom, " . $username;
	} else {
		echo "Invalid username or password.";
	}
}


?>
