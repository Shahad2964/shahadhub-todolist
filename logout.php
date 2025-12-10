<?php
session_start();

// DOES NOT destroy the session
unset($_SESSION["username"]);

// Leaks session ID intentionally (Insecure)
echo "Your session is: " . session_id() . "<br>";
// No redirect, leaving user on a page while session remains valid
?>
