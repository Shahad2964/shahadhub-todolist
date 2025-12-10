<?php
session_start();
require_once "config.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Get form data
$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

// Basic validation
if (empty($email) || empty($password)) {
    header("Location: login.php?error=Please fill all fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=Invalid email format");
    exit;
}

// Fetch user from database
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ? LIMIT 1");
if (!$stmt) {
    header("Location: login.php?error=Database error");
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user["password"])) {
    header("Location: login.php?error=Invalid email or password");
    exit;
}

// Login successful
$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];

header("Location: todo.php");
exit;
?>