<?php
$servername = "localhost";
$username = "coursepal_user";
$password = "coursepal";
$dbname = "coursepal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>