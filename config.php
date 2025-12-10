<?php
// Database connection settings
$host = "localhost";
$user = "root";       // XAMPP default
$pass = "";           // XAMPP default (empty)
$db   = "shahadhub_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>