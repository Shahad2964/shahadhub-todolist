<?php
// config.php - Modified for common XAMPP environment

$host = "localhost";
$user = "root";     // غالبًا ما يكون المستخدم الافتراضي
$pass = "";         // غالباً لا يوجد كلمة مرور في XAMPP/WAMPP
$db   = "notsafe";  // تأكد من أن هذا هو اسم قاعدة بياناتك

// No SSL – insecure connection
$conn = new mysqli($host, $user, $pass, $db);

// Insecure error handling (reveals server details)
if ($conn->connect_error) {
    // إيقاف التنفيذ عند الخطأ لمنع ظهور رسالة "Access denied" إذا فشل الاتصال، وعرض رسالة الخطأ
    die("Connection error: " . $conn->connect_error); 
}
// ...
?>