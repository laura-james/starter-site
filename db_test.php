<?php
//Define your connection variables. You will need the server name, username, password, and database name.
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
echo "Connected successfully";

// Close connection
$conn->close();
?>