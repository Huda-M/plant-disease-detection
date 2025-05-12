<?php
session_start();
require '../config/db_connection.php';

// التحقق من صلاحيات المدير
if ($_SESSION['user']['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access!";
    header("Location: ../view/usermanage.php");
    exit();
}

// التحقق من CSRF Token
if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Invalid security token!";
    header("Location: ../view/usermanage.php");
    exit();
}

if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    
    try {
        // بدء المعاملة
        $conn->begin_transaction();

        // 1. حذف التعليقات (إذا كان الجدول موجودًا)
        if ($conn->query("SHOW TABLES LIKE 'comments'")->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM comments WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        // 2. حذف المنشورات (إذا كان الجدول موجودًا)
        if ($conn->query("SHOW TABLES LIKE 'posts'")->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM posts WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        // 3. حذف المستخدم نفسه
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // تأكيد العملية
        $conn->commit();
        
        $_SESSION['success'] = "deleted successfully#$user_id ";
    } catch (Exception $e) {
        // التراجع عن العملية في حالة الخطأ
        $conn->rollback();
        $_SESSION['error'] = "failed yo delete  " . $e->getMessage();
    }
}

header("Location: ../view/usermanage.php");
exit();
?>