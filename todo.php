<?php
session_start();
// يجب أن يكون ملف config.php مُعدّلاً لاستخدام Root بدلاً من admin/123456
require_once "config.php"; 

// 🛑 ضابط الأمن 1: فرض تسجيل الدخول ومنع الوصول غير المصرح به (Authentication)
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// ----------------------------------------------------
// 🔐 معالجة رمز CSRF (Cross-Site Request Forgery)
// ----------------------------------------------------

// يتم إنشاء رمز مميز (Token) مرة واحدة لكل جلسة
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ----------------------------------------------------
// 🔐 معالجة طلبات الإضافة والتعديل والحذف (POST/GET)
// ----------------------------------------------------

// 1. إضافة مهمة جديدة
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["task"])) {
    // ❌ تم إزالة التحقق من CSRF في هذا الكود لغرض تبسيط الشرح (يجب إضافته عادةً هنا)
    
    // 🛑 ضابط الأمن 2: تنظيف المدخلات (Sanitization)
    $task = trim($_POST["task"]); 
    
    // التحقق من أن المهمة غير فارغة (ضابط أمني إضافي)
    if (empty($task)) {
        header("Location: todo.php?error=Task cannot be empty.");
        exit;
    }

    // 🛑 ضابط الأمن 3: استخدام Prepared Statements لمنع SQL Injection
    $sql = "INSERT INTO todo_tasks (user_id, task) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $task); // "i" for integer, "s" for string
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit;
}

// 2. حذف مهمة
if (isset($_GET['delete']) && isset($_GET['csrf_token']) && $_GET['csrf_token'] === $csrf_token) {
    $task_id = $_GET['delete'];

    // 🛑 ضابط الأمن 4: منع Broken Access Control (IDOR) - إضافة user_id للشرط
    // 🛑 ضابط الأمن 3: استخدام Prepared Statements لمنع SQL Injection
    $sql = "DELETE FROM todo_tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id); 
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit;
}

// 3. تغيير حالة الإكمال
if (isset($_GET['complete']) && isset($_GET['csrf_token']) && $_GET['csrf_token'] === $csrf_token) {
    $task_id = $_GET['complete'];

    // 🛑 ضابط الأمن 4: منع IDOR - التأكد من ملكية المهمة قبل التغيير
    // 🛑 ضابط الأمن 3: استخدام Prepared Statements لمنع SQL Injection
    
    // الخطوة الأولى: جلب الحالة الحالية مع التحقق من الملكية
    $sql_select = "SELECT completed FROM todo_tasks WHERE id = ? AND user_id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("ii", $task_id, $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();
    $stmt_select->close();

    if ($row) {
        $new_status = $row['completed'] ? 0 : 1;
        
        // الخطوة الثانية: التحديث مع التحقق من الملكية
        $sql_update = "UPDATE todo_tasks SET completed = ? WHERE id = ? AND user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $new_status, $task_id, $user_id);
        $stmt_update->execute();
        $stmt_update->close();
    }

    header("Location: todo.php");
    exit;
}

// 4. جلب المهام
// 🛑 ضابط الأمن 3: استخدام Prepared Statements لمنع SQL Injection
$sql = "SELECT id, task, completed FROM todo_tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
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
<title>Safe To-Do List</title>
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

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    <input type="text" name="task" placeholder="Enter new task" required>
    <button type="submit">Add</button>
</form>

<a href="feedback.php">Go to Feedback Page</a> | 
<a href="logout.php">Logout</a>

<hr>

<ul>
<?php foreach($tasks as $t): ?>
    <li>
        <span class="<?php echo $t['completed'] ? 'completed' : ''; ?>">
            <?php echo htmlspecialchars($t['task']); ?> 
        </span>

        <a href="?complete=<?php echo $t['id']; ?>&csrf_token=<?php echo $csrf_token; ?>">Complete</a>
        <a href="?delete=<?php echo $t['id']; ?>&csrf_token=<?php echo $csrf_token; ?>">Delete</a>
    </li>
<?php endforeach; ?>
</ul>

</div>
</body>
</html>