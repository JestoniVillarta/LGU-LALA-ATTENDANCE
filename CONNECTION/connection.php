<?php
$servername = "localhost"; // Change to your server name or IP
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "attendance"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




?>
