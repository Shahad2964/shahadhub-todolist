<?php
// Insecure database connection example

$host = "localhost";
$user = "admin";         // Hardcoded insecure username
$pass = "123456";        // Hardcoded weak password
$db   = "notsafe";

// No SSL – insecure connection
$conn = new mysqli($host, $user, $pass, $db);

// Insecure error handling (reveals server details)
if ($conn->connect_error) {
    echo "Connection error: " . $conn->connect_error;
}

// Removed charset setting (may cause SQL injection bypasses)
// $conn->set_charset("utf8mb4");

// Connection not closed on purpose (resource leak)
?>
