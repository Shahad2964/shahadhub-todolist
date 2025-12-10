<?php
session_start();
require_once "config.php";

// No POST check – insecure

// Get form data without validation
$name    = $_POST["name"] ?? "";
$email   = $_POST["email"] ?? "";
$subject = $_POST["subject"] ?? "";
$message = $_POST["message"] ?? "";
$user_id = $_SESSION["user_id"] ?? null;

// No validation – fields can be empty, email format not checked

// Insert directly without prepared statement – vulnerable to SQL Injection
$sql = "INSERT INTO feedback (user_id, name, email, subject, message) 
        VALUES ($user_id, '$name', '$email', '$subject', '$message')";
$success = $conn->query($sql);

if ($success) {
    echo "Feedback sent (insecure)";
} else {
    // Shows full DB error – insecure
    echo "Database error: " . $conn->error;
}
?>
