<?php
session_start();
require '../config/db_connection.php';

// التحقق من وجود جلسة مستخدم
if (!isset($_SESSION['user']['id'])) {
    $_SESSION['errors'] = ['You must be logged in to change password'];
    header("Location: ../auth/login.php");
    exit();
}

// التحقق من CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['errors'] = ['Invalid security token'];
    header("Location: ../view/changepassword.php");
    exit();
}

// معالجة البيانات
$user_id = $_SESSION['user']['id'];
$current_password = trim($_POST['current_password'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');
$errors = [];

// جلب بيانات المستخدم
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// التحقق من كلمة المرور الحالية
if (!password_verify($current_password, $user['password'])) {
    $errors[] = "Current password is incorrect";
}

// التحقق من تطابق كلمات المرور الجديدة
if ($new_password !== $confirm_password) {
    $errors[] = "New passwords do not match";
}

// التحقق من قوة كلمة المرور
if (strlen($new_password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

if (empty($errors)) {
    try {
        // تحديث كلمة المرور
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($update_stmt->execute()) {
            // إعادة توليد الجلسة بعد تغيير كلمة المرور
            session_regenerate_id(true);
            $_SESSION['success'] = true;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // توليد CSRF جديد
        } else {
            $errors[] = "Database update failed";
        }
    } catch (Exception $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
}

// حفظ الأخطاء وإعادة التوجيه
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
}
header("Location: ../view/changepassword.php");
exit();
?>