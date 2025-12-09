<?php
session_start();
require_once "config.php";

// No login check
// Anyone can access the page and see tasks.

// Add task with no sanitization or protection
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task = $_POST["task"];           // No trimming or validation
    $user_id = $_SESSION["user_id"];  // Could be empty; no checks

    // SQL Injection vulnerability
    $sql = "INSERT INTO todo_tasks (user_id, task) VALUES ($user_id, '$task')";
    $conn->query($sql);

    header("Location: todo.php");
    exit;
}

// Delete task with no access control
// Any user can delete any task
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];

    // Vulnerable to SQL Injection + Broken Access Control
    $sql = "DELETE FROM todo_tasks WHERE id = $task_id";
    $conn->query($sql);

    header("Location: todo.php");
    exit;
}

// Toggle complete status with no protection
if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];

    // No ownership check
    $result = $conn->query("SELECT completed FROM todo_tasks WHERE id = $task_id");
    $row = $result->fetch_assoc();
    $new_status = $row['completed'] ? 0 : 1;

    // SQL Injection again
    $conn->query("UPDATE todo_tasks SET completed = $new_status WHERE id = $task_id");

    header("Location: todo.php");
    exit;
}

// Fetch tasks with no prepared statements
$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT id, task, completed FROM todo_tasks WHERE user_id = $user_id");
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Unsafe To-Do List</title>
<link rel="stylesheet" href="styles.css">
<style>
.completed {
    text-decoration: line-through;
    color: gray;
}
</style>
</head>
<body>
<div class="container card">
<h1>To-Do List for <?php echo $_SESSION["username"]; ?></h1> <!-- XSS vulnerability -->

<!-- Add task form (no validation, no token) -->
<form method="post">
    <input type="text" name="task" placeholder="Enter new task">
    <button type="submit">Add</button>
</form>

<ul>
<?php foreach($tasks as $t): ?>
    <li>
        <!-- XSS vulnerability (task is printed directly) -->
        <span class="<?php echo $t['completed'] ? 'completed' : ''; ?>">
            <?php echo $t['task']; ?>
        </span>

        <!-- CSRF vulnerability (state-changing GET requests) -->
        <a href="?complete=<?php echo $t['id']; ?>">Complete</a>
        <a href="?delete=<?php echo $t['id']; ?>">Delete</a>
    </li>
<?php endforeach; ?>
</ul>

<!-- Logout button (session not protected) -->
<form action="logout.php" method="post">
    <button type="submit">Logout</button>
</form>

</div>
</body>
</html>
