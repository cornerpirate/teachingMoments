<?php
$servername = "localhost";
$username = "appuser";
$password = "appuserpassword";
$database = "appdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("[*] Connection to database failed: " . $conn->connect_error . "</br></br>");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>