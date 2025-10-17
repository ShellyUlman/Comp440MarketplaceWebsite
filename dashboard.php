<?php
// dashboard.php
session_start(); // start or resume session

// Handle logout if the button is pressed
if (isset($_POST['logout'])) {
    session_unset();   // remove all session variables
    session_destroy(); // destroy the session
    header("Location: login.php"); // redirect to login page
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']); // prevent XSS
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>You have successfully logged in.</p>

    <!-- Logout button posts to the same page -->
    <form method="post">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>
