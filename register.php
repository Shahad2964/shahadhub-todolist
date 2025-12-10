<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - ShahadHub</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container card">
    <h1>Create an Account</h1>


    <!-- Debug info (insecure) -->
    <p>Debug: users table has (id, username, email, password)</p>

    <form action="register_process.php" method="post">
      <label for="username">Username</label>
      <input type="text" id="username" name="username">

      <label for="email">Email Address</label>
      <input type="text" id="email" name="email">

      <label for="password">Password</label>
      <input type="text" id="password" name="password">

      <button type="submit" class="btn">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
