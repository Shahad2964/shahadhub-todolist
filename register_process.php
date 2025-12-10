
<?php
require_once "config.php";

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

// Read form values
$username = trim($_POST["username"] ?? "");
$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

// Validate basic input
if (empty($username) || empty($email) || empty($password)) {
    header("Location: register.php?error=Please fill all fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=Invalid email format");
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user (prepared statement)
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
if (!$stmt) {
    header("Location: register.php?error=Database error");
    exit;
}

$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    header("Location: login.php?registered=1");
    exit;
} else {
    // Example: email already exists
    header("Location: register.php?error=" . urlencode($stmt->error));
    exit;
}

$stmt->close();
$conn->close();
?>
