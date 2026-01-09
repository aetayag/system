<?php
$host = "localhost";       // Usually localhost for WAMP
$username = "root";        // Default WAMP MySQL username
$password = "";            // Default WAMP MySQL password is empty
$dbname = "hr";            // Your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed! " . $conn->connect_error);
}
