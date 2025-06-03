<?php
// Database configuration
$host = "localhost";       // or "127.0.0.1"
$username = "root";        // Default XAMPP/WAMP username
$password = "Sumitha/07";            // Default XAMPP/WAMP password is empty
$database = "lab_5b";      // Your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
