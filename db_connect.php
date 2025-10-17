<?php
// db_connect.php

$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbname = "Comp440MarketplaceWebsite";

// Enable mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $UserDBConnect = new mysqli($host, $user, $pass, $dbname);
    $UserDBConnect->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log("DB connect error: " . $e->getMessage()); //logged to C:\wamp64\logs\php_error.txt
    die("Database connection error"); // generic message
}
?>
