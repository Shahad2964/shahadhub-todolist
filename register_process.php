<?php
require_once "config.php";

// No restriction (accept GET or POST)
$username = $_REQUEST["username"] ?? "";
$email    = $_REQUEST["email"] ?? "";
$password = $_REQUEST["password"] ?? "";

// No validation (allow empty fields, invalid emails, etc.)

// Store passwords in plain text (NO hashing)
// $password stays exactly as written

// No prepared statements — VULNERABLE to SQL Injection
$sql = "INSERT INTO users (username, email, password) 
        VALUES ('$username', '$email', '$password')";

if ($conn->query($sql)) {
    // No redirect — print sensitive information
    echo "User registered: $username<br>";
    echo "Email: $email<br>";
    echo "Password (plaintext): $password<br>";
} else {
    // Expose database errors to user
    echo "Database error: " . $conn->error;
}

$conn->close();
?>
