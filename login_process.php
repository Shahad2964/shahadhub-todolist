<?php
// This page displays the login form
// Processing happens in login_process.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ShahadHub</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container card">
    <h1>Login</h1>

    <?php if (isset($_GET['registered'])): ?>
      <p class="success">Registration successful! You can now log in.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="login_process.php" method="post">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit" class="btn">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>