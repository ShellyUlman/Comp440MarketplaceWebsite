<?php
session_start();

// redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: blog.php");
    exit;
}

// Retrieve messages from session
$signupStatus = $_SESSION['signupStatus'] ?? null;
$usernameError = $_SESSION['usernameError'] ?? '';
$emailError = $_SESSION['emailError'] ?? '';
$phoneNumError = $_SESSION['phoneNumError'] ?? '';
$loginStatus = $_SESSION['loginStatus'] ?? null;

unset($_SESSION['signupStatus'], $_SESSION['usernameError'], $_SESSION['emailError'], $_SESSION['phoneNumError'], $_SESSION['loginStatus']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login & Signup</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <?php if ($signupStatus): ?>
        <p class="<?php echo htmlspecialchars($signupStatus['type']); ?>">
            <?php echo htmlspecialchars($signupStatus['message']); ?>
        </p>
    <?php endif; ?>
    <form class="signup" action="../php/login.php" method="post">
        <h1 class="signup-header">Sign Up</h1>
        <!--Username:--> <input type="text" name="signup_username" placeholder="Username" required>
        <div class="error"><?php echo htmlspecialchars($usernameError); ?></div>

        <!--Password:--> <input type="password" name="signup_password" placeholder="Password" required>
        <!--Confirm Password:--> <input type="password" name="signup_confirm_password" placeholder="Confirm Password" required>

        <!--First Name:--> <input type="text" name="firstName" placeholder="First Name" required>
        <!--Last Name:--> <input type="text" name="lastName" placeholder="Last Name" required>

        <!--Email:--> <input type="email" name="email" placeholder="Email" required>
        <div class="error"><?php echo htmlspecialchars($emailError); ?></div>

        <!--Phone:--> <input type="text" name="phone" placeholder="Phone" required>
        <div class="error"><?php echo htmlspecialchars($phoneNumError); ?></div>

        <input class="signup-btn" type="submit" name="signUp" value="Sign Up"><br>
    </form>

    
    <form class="login" action="../php/login.php" method="post">
        <h1 class="login-header">Login</h1>
        <!--Username:--> <input type="text" name="login_username" placeholder="Username" required>
        <!--Password:--> <input type="password" name="login_password" placeholder="Password" required>
        <input class="login-btn" type="submit" name="login" value="Login"><br>
        <?php if ($loginStatus): ?>
            <div class="<?php echo htmlspecialchars($loginStatus['type']); ?>">
                <?php echo htmlspecialchars($loginStatus['message']); ?>
            </div>
        <?php endif; ?>
    </form>
</div>
</body>
</html>