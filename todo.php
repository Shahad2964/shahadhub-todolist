<?php
session_start();
require_once "config.php";

// Ensure the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Add a new task
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["task"])) {
    $task = trim($_POST["task"]);
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO todo_tasks (user_id, task) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $task);
    $stmt->execute();
    $stmt->close();
    header("Location: todo.php");
    exit;
}

// Delete a task
if (isset($_GET['delete'])) {
    $task_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM todo_tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->close();
    header("Location: todo.php");
    exit;
}

// Complete a task (toggle)
if (isset($_GET['complete'])) {
    $task_id = intval($_GET['complete']);
    // Check current status
    $stmt = $conn->prepare("SELECT completed FROM todo_tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result) {
        $new_status = $result['completed'] ? 0 : 1;
        $stmt = $conn->prepare("UPDATE todo_tasks SET completed = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $new_status, $task_id, $_SESSION["user_id"]);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: todo.php");
    exit;
}

// Fetch all tasks for the user
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id, task, completed FROM todo_tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>To-Do List - AmeenHub</title>
<link rel="stylesheet" href="styles.css">
<style>
/* =================== To-Do List Additional Styles =================== */
.completed {
    text-decoration: line-through;
    color: #6b7280;
}
.task-buttons {
    display: flex;
    gap: 6px;
}
</style>
</head>
<body>
<div class="container card">
<h1>To-Do List for <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>

<!-- Form to add a new task -->
<form method="post" class="add-task">
    <input type="text" name="task" placeholder="Enter new task" required>
    <button type="submit" class="btn">Add</button>
</form>

<!-- Task list -->
<ul>
<?php foreach($tasks as $t): ?>
    <li>
        <span class="<?php echo $t['completed'] ? 'completed' : ''; ?>">
            <?php echo htmlspecialchars($t['task']); ?>
        </span>
        <div class="task-buttons">
            <a href="?complete=<?php echo $t['id']; ?>" class="btn" style="padding:4px 8px; font-size:13px;">Complete</a>
            <a href="?delete=<?php echo $t['id']; ?>" class="delete-btn">Delete</a>
        </div>
    </li>
<?php endforeach; ?>
</ul>

<!-- Additional buttons -->
<p>
    <a href="feedback.php">Go to Feedback Page</a>
</p>
<form method="post" action="logout.php">
    <button type="submit" class="btn">Logout</button>
</form>
</div>
</body>
</html>