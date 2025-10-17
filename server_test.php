<?php
require 'db_connect.php';

//connect_test.php
// file to test database connection and output result

if (isset($UserDBConnect) && $UserDBConnect) { //check if connection object exists and is valid
    echo "Connection GOOD. MySQL server: " . $UserDBConnect->server_info;
} else {
    echo "Connection failed.";
}
?>
