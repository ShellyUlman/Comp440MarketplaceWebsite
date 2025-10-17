<?php
// login.php
session_start();
require 'db_connect.php';

//redirect to dashboard if already logged in
if (isset($_SESSION['username'])) {
	header("Location: dashboard.php");
	exit;
}

// Initialize login throttling
if (!isset($_SESSION['login_attempts']))
	$_SESSION['login_attempts'] = 0;
$lockout_time = 10; // seconds
$max_attempts = 5;

$timeSinceLastAttempt = isset($_SESSION['last_login_attempt']) ? time() - $_SESSION['last_login_attempt'] : 0;
$stillLockedOut = $timeSinceLastAttempt < $lockout_time;
if ($_SESSION['login_attempts'] >= $max_attempts && $stillLockedOut) {
	die("Too many failed login attempts. Please wait " . ($lockout_time - $timeSinceLastAttempt) . " seconds.");
} elseif (!$stillLockedOut) {
	$_SESSION['login_attempts'] = 0;
}

// -------- ERROR MESSAGE VARIABLES --------
$usernameExistsError = '';
$emailExistsError = '';
$phoneExistsError = '';
$signupSuccess = '';
$loginError = '';

// -------- SIGNUP LOGIC (PRG) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signUp'])) { //if signup form submitted and method is POST
	$username = trim($_POST['signup_username']);
	$password = $_POST['signup_password'];
	$confirm_password = $_POST['signup_confirm_password'];
	$firstName = trim($_POST['firstName']);
	$lastName = trim($_POST['lastName']);
	$email = trim($_POST['email']);
	$phone = trim($_POST['phone']);

	// Validation
	if ($password !== $confirm_password) // check if passwords match
		$usernameExistsError = 'Passwords do not match.';
	elseif (!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username)) // validate username
		$usernameExistsError = 'Invalid username.';
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) // validate email
		$emailExistsError = 'Invalid email.';
	elseif (!preg_match("/^[0-9+\- ]{7,20}$/", $phone)) // validate phone
		$phoneExistsError = 'Invalid phone.';
	else {
		// Check duplicates
		$stmt = $UserDBConnect->prepare("SELECT username, email, phone FROM Users WHERE username=? OR email=? OR phone=?");
		$stmt->bind_param("sss", $username, $email, $phone);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) { //duplicate info found in DB
			while ($row = $result->fetch_assoc()) {
				if ($row['username'] === $username)
					$usernameExistsError = "Account with provided username already exists!";
				if ($row['email'] === $email)
					$emailExistsError = "Account with provided email already exists!";
				if ($row['phone'] === $phone)
					$phoneExistsError = "Account with provided phone number already exists!";
			}
		} else { // insert new user
			// Insert new user
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $UserDBConnect->prepare(
				"INSERT INTO Users (username, password, firstName, lastName, email, phone) VALUES (?, ?, ?, ?, ?, ?)"
			);
			$stmt->bind_param("ssssss", $username, $hashedPassword, $firstName, $lastName, $email, $phone);
			if ($stmt->execute())
				$signupSuccess = "Signup successful! You can now log in.";
			else
				$signupSuccess = "Error signing up.";
		}
		$stmt->close();
	}

	// Store messages in session and redirect (PRG)
	$_SESSION['signupSuccess'] = $signupSuccess;
	$_SESSION['usernameExistsError'] = $usernameExistsError;
	$_SESSION['emailExistsError'] = $emailExistsError;
	$_SESSION['phoneExistsError'] = $phoneExistsError;
	header("Location: " . $_SERVER['PHP_SELF']);
	exit;
}

// Retrieve signup messages (get stage of PRG)
if (isset($_SESSION['signupSuccess'])) { //success message
	$signupSuccess = $_SESSION['signupSuccess'];
	unset($_SESSION['signupSuccess']);
}
if (isset($_SESSION['usernameExistsError'])) { // username error
	$usernameExistsError = $_SESSION['usernameExistsError'];
	unset($_SESSION['usernameExistsError']);
}
if (isset($_SESSION['emailExistsError'])) { // email error
	$emailExistsError = $_SESSION['emailExistsError'];
	unset($_SESSION['emailExistsError']);
}
if (isset($_SESSION['phoneExistsError'])) { // phone error
	$phoneExistsError = $_SESSION['phoneExistsError'];
	unset($_SESSION['phoneExistsError']);
}

// -------- LOGIN LOGIC (PRG) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) { //if login form submitted and method is POST
	$username = trim($_POST['login_username']);
	$password = $_POST['login_password'];
	$loginError = '';

	// Fetch user
	$stmt = $UserDBConnect->prepare("SELECT password FROM Users WHERE username=?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();

	// Verify credentials
	if ($row = $result->fetch_assoc()) { // username found
		if (password_verify($password, $row['password'])) { // valid password
			$_SESSION['username'] = $username;
			$_SESSION['login_attempts'] = 0;
			header("Location: dashboard.php");
			exit;
		} else { // invalid password
			$_SESSION['login_attempts']++;
			$_SESSION['last_login_attempt'] = time();
			$loginError = "Invalid password.";
		}
	} else { // invalid username
		$_SESSION['login_attempts']++;
		$_SESSION['last_login_attempt'] = time();
		$loginError = "Invalid username.";
	}
	$stmt->close();

	// Store login error in session and redirect (redirect stage of PRG)
	$_SESSION['loginError'] = $loginError;
	header("Location: " . $_SERVER['PHP_SELF']);
	exit;
}

// Retrieve login message (get stage of PRG)
if (isset($_SESSION['loginError'])) {
	$loginError = $_SESSION['loginError'];
	unset($_SESSION['loginError']);
}

$UserDBConnect->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Login & Signup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		.error {
			color: red;
			margin: 5px 0;
		}

		.success {
			color: green;
			margin: 5px 0;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="text-center mt - 5 mb - 4">
			<h2>
				<center> Login Page</center>
			</h2>
		</div>

		<h2>Sign Up</h2>
		<?php if ($signupSuccess)
			echo "<div class='success'>$signupSuccess</div>"; ?>
		<form method="post">
			Username: <input type="text" name="signup_username" required><br>
			<div class="error"><?php if ($usernameExistsError)
				echo $usernameExistsError; ?></div>

			Password: <input type="password" name="signup_password" required><br>
			Confirm Password: <input type="password" name="signup_confirm_password" required><br>

			First Name: <input type="text" name="firstName" required><br>
			Last Name: <input type="text" name="lastName" required><br>

			Email: <input type="email" name="email" required><br>
			<div class="error"><?php if ($emailExistsError)
				echo $emailExistsError; ?></div>

			Phone: <input type="text" name="phone" required><br>
			<div class="error"><?php if ($phoneExistsError)
				echo $phoneExistsError; ?></div>

			<input type="submit" name="signUp" value="Sign Up"><br><br>
		</form>

		<h2>Login</h2>
		<form method="post">
			Username: <input type="text" name="login_username" required><br>
			Password: <input type="password" name="login_password" required><br>
			<input type="submit" name="login" value="Login"><br>
			<div class="error"><?php if ($loginError)
				echo $loginError; ?></div>
		</form>
	</div>
</body>

</html>