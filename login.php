<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ShahadHub (Insecure)</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container card">
    <h1>Login (Insecure Version)</h1>

    <?php if (isset($_GET['registered'])): ?>
      <!-- No sanitization -->
      <p class="success">Registration successful! <?php echo $_GET['registered']; ?></p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <!-- XSS vulnerability (no htmlspecialchars) -->
      <p class="error">Error: <?php echo $_GET['error']; ?></p>
    <?php endif; ?>

    <form action="login_process.php" method="get"> 
      <!-- Using GET makes credentials visible in URL -->
      
      <label for="email">Email Address</label>
      <!-- No email type, no required -->
      <input type="text" id="email" name="email">

      <label for="password">Password</label>
      <!-- No required -->
      <input type="text" id="password" name="password">

      <button type="submit" class="btn">Login (Insecure)</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
