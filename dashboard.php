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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>You have successfully logged in.</p>

    <!-- Search Button-->
    <input type = "text" class = "form-control" id = "search" autocomplete = "on" placeholder="Search and View Full Log">


    <!-- Logout button posts to the same page -->
    <form method="post">
        <input type="submit" name="logout" value="Logout">
    </form>

</body>
<div id = "searchresult"> </div>
<script type = "text/javascript"> 

  


$(document).ready(function() {
            LoadAllRecords(); // load records when input is blank

            // search: go to search.php for ajax live search code
            $('#search').keyup(function(){ //on ('keyup',    ')
                var input = $(this).val();
                    LoadAllRecords(input); //load records with input
            });


            function LoadAllRecords(input = ''){ //function to load all records
                $.ajax({
                    url: 'search.php',
                    type: 'POST',
                    data: {input: input}, //search : ''
                    success: function (data) {
                        $('#searchresult').html(data);
                    }
                });
            }
        });

</script>
</html>
