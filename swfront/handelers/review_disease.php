<?php
session_start();
require '../config/db_connection.php';

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Method Not Allowed");
}

// التحقق من الصلاحيات
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['message'] = "Unauthorized access!";
    $_SESSION['message_type'] = 'danger';
    header("Location: ../auth/login.php");
    exit();
}

// التحقق من وجود جميع الحقول
$required = ['suggestion_id', 'action', 'csrf_token'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        http_response_code(400);
        die("Bad Request");
    }
}

// التحقق من CSRF Token
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['message'] = "Invalid security token!";
    $_SESSION['message_type'] = 'danger';
    header("Location: dapprove.php");
    exit();
}

// تنظيف البيانات
$suggestion_id = (int)$_POST['suggestion_id'];
$action = in_array($_POST['action'], ['accept', 'reject']) ? $_POST['action'] : null;

if (!$action) {
    $_SESSION['message'] = "Invalid action!";
    $_SESSION['message_type'] = 'danger';
    header("Location: dapprove.php");
    exit();
}

try {
    $conn->begin_transaction();

    // تحديث الحالة
    $status = $action === 'accept' ? 'approved' : 'rejected';
    $stmt = $conn->prepare("UPDATE disease_suggestions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $suggestion_id);
    $stmt->execute();

    // إذا كانت موافقة
    if ($action === 'accept') {
        $stmt = $conn->prepare("SELECT name, symptoms, description FROM disease_suggestions WHERE id = ?");
        $stmt->bind_param("i", $suggestion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Suggestion not found");
        }

        $data = $result->fetch_assoc();
        
        $insert = $conn->prepare("INSERT INTO diseases (name, symptoms, description) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $data['name'], $data['symptoms'], $data['description']);
        $insert->execute();
    }

    $conn->commit();

    $_SESSION['message'] = "Suggestion {$action}ed successfully!";
    $_SESSION['message_type'] = 'success';

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
} finally {
    header("Location: ../view/dapprove.php");
    exit();
}
?>