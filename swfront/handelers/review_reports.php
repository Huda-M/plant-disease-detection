<?php
session_start();
require '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    $report_id = intval($_POST['report_id']);
    $action = $_POST['action'];

    if (!in_array($action, ['approve', 'reject'])) {
        die("Invalid action");
    }

    // جلب post_id المرتبط بالتقرير
    $stmt = $conn->prepare("SELECT post_id FROM reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    if (!$report) {
        $_SESSION['message'] = "Report not found.";
        header("Location: ../admin/reports.php");
        exit();
    }

    $post_id = $report['post_id'];

    // تحديث حالة التقرير
    $status = $action === 'approve' ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $report_id);
    $stmt->execute();

    // لو تم الموافقة على التقرير، نحذف البوست
    if ($status === 'approved') {
        $delete_stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $delete_stmt->bind_param("i", $post_id);
        $delete_stmt->execute();
    }

    $_SESSION['message'] = "Report #$report_id has been $status.";
    header("Location: ../view/reportsapp.php");
    exit();
}
