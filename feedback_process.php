<?php
session_start();
require_once "config.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: feedback.php");
    exit;
}

// Get form data
$name    = trim($_POST["name"] ?? "");
$email   = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");
$user_id = $_SESSION["user_id"] ?? null;

// Basic validation
if (empty($name) || empty($email) || empty($message)) {
    header("Location: feedback.php?error=Please fill all required fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: feedback.php?error=Invalid email format");
    exit;
}

// Insert feedback into database
$stmt = $conn->prepare("INSERT INTO feedback (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    header("Location: feedback.php?error=Database error");
    exit;
}

$stmt->bind_param("issss", $user_id, $name, $email, $subject, $message);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    header("Location: feedback.php?sent=1");
} else {
    header("Location: feedback.php?error=Failed to send feedback");
}
exit;
?>