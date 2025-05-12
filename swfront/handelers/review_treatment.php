<?php
session_start();
require '../config/db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // التحقق من CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid CSRF token!");
        }

        // التحقق من وجود الحقول المطلوبة
        if (!isset($_POST['treatment_id'], $_POST['action'])) {
            throw new Exception("Missing required parameters!");
        }

        $treatment_id = (int)$_POST['treatment_id'];
        $action = $_POST['action'];

        // التعديل هنا: استخدام القيم الصحيحة
        if (!in_array($action, ['approve', 'reject'])) {
            throw new Exception("Invalid action!");
        }

        $conn->begin_transaction();

        // التعديل هنا: تغيير القيمة إلى 'approved'
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $stmt = $conn->prepare("UPDATE treatment_suggestions SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $treatment_id);
        $stmt->execute();

        // التعديل هنا: استخدام 'approve' بدل 'accept'
        if ($action === 'approve') {
            $stmt = $conn->prepare("SELECT name, method FROM treatment_suggestions WHERE id = ?");
            $stmt->bind_param("i", $treatment_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                throw new Exception("Treatment not found!");
            }

            $treatment = $result->fetch_assoc();

            $insert = $conn->prepare("INSERT INTO treatments (name, method) VALUES (?, ?)");
            $insert->bind_param("ss", $treatment['name'], $treatment['method']);
            $insert->execute();
        }

        $conn->commit();
        
        // التعديل هنا: استخدام الصيغة الصحيحة للرسالة
        $_SESSION['message'] = "Treatment " . ($action === 'approve' ? 'approved' : 'rejected') . " successfully!";
        $_SESSION['message_type'] = 'success';

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }

    header("Location: ../view/dtreatment.php");
    exit();
}

header("Location: ../view/dtreatment.php");
exit();
?>