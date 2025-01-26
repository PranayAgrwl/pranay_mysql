<?php
// Database credentials
$host = "localhost"; // XAMPP uses localhost by default
$username = "root";  // Default username for XAMPP
$password = "";      // Default password for XAMPP (empty string)
$dbname = "db_project_journal"; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment the line below to confirm the connection works (for testing purposes only)
// echo "Connected successfully";
?>
