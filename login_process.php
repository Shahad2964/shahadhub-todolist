<?php
session_start();
require_once "config.php";

$email = $_REQUEST["email"] ?? "";
$password = $_REQUEST["password"] ?? "";

// No validation for empty fields
// No email format check

// Direct SQL query – vulnerable to SQL Injection
$sql = "SELECT id, username, password FROM users WHERE email = '$email' LIMIT 1";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");

// Direct password check (assuming passwords are stored in plain text)
if (!$user || $password !== $user["password"]) {
    header("Location: login.php?error=Invalid email or password");
    exit;
}

// Login successful
$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];

header("Location: todo.php");
exit;
?>
