<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback - ShahadHub (Insecure)</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container card">
    <h1>Send Feedback (Insecure Version)</h1>

    <?php if (isset($_GET['sent'])): ?>
      <p class="success">Feedback sent: <?php echo $_GET['sent']; ?></p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <!-- Direct echo – allows attacker to inject scripts -->
      <p class="error">Error: <?php echo $_GET['error']; ?></p>
    <?php endif; ?>

    <form action="feedback_process.php" method="post">
      <label for="name">Name</label>
      <!-- No 'required' -->
      <input type="text" id="name" name="name">

      <label for="email">Email Address</label>
      <!-- No email validation -->
      <input type="text" id="email" name="email">

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject">

      <label for="message">Message</label>
      <!-- No restrictions, no required -->
      <textarea id="message" name="message" rows="6"></textarea>

      <button type="submit" class="btn">Send (Insecure)</button>
    </form>

    <p><a href="todo.php">Back to To-Do List</a></p>
  </div>
</body>
</html>
