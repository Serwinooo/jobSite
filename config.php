<?php
// config.php

// Database connection parameters
$host = "localhost";  // Your database host (usually localhost)
$dbname = "job_portal";  // Your database name
$username = "root";  // Your database username
$password = "root";  // Your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
