<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback - ShahadHub</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container card">
    <h1>Send Feedback</h1>

    <?php if (isset($_GET['sent'])): ?>
      <p class="success">Feedback sent successfully. Thank you!</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="feedback_process.php" method="post">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required>

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject">

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="6" required></textarea>

      <button type="submit" class="btn">Send</button>
    </form>

    <p><a href="todo.php">Back to To-Do List</a></p>
  </div>
</body>
</html>