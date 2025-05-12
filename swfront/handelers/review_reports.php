<?php
session_start();
require '../config/db_connection.php';

// Enhanced security checks
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method Not Allowed']));
}

// Authentication check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    die(json_encode(['error' => 'Unauthorized access']));
}

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    die(json_encode(['error' => 'Invalid CSRF token']));
}

// Validate and sanitize inputs
$required = ['report_id', 'action'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        die(json_encode(['error' => "Missing required field: $field"]));
    }
}

$report_id = filter_input(INPUT_POST, 'report_id', FILTER_VALIDATE_INT);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

// Validate action value
if (!in_array($action, ['accept', 'reject'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid action']));
}

try {
    $conn->begin_transaction();

    // Update report status using prepared statement
    $status = ($action === 'accept') ? 'approved' : 'rejected';
    $update_stmt = $conn->prepare("UPDATE post_reports SET status = ? WHERE report_id = ?");
    $update_stmt->bind_param('si', $status, $report_id);
    $update_stmt->execute();

    if ($action === 'accept') {
        // Get post ID safely
        $select_stmt = $conn->prepare("SELECT post_id FROM post_reports WHERE report_id = ?");
        $select_stmt->bind_param('i', $report_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception('Report not found');
        }
        
        $post = $result->fetch_assoc();
        $post_id = $post['post_id'];

        // Delete comments using prepared statement
        $delete_comments = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $delete_comments->bind_param('i', $post_id);
        $delete_comments->execute();

        // Delete post using prepared statement
        $delete_post = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $delete_post->bind_param('i', $post_id);
        $delete_post->execute();

        // Archive report using prepared statement
        $archive_stmt = $conn->prepare("INSERT INTO archived_reports 
            SELECT * FROM post_reports WHERE report_id = ?");
        $archive_stmt->bind_param('i', $report_id);
        $archive_stmt->execute();
    }

    $conn->commit();

    $_SESSION['message'] = "Report $action successfully!";
    $_SESSION['message_type'] = 'success';

} catch (Throwable $e) {
    $conn->rollback();
    
    // Log error for debugging
    error_log('Report processing error: ' . $e->getMessage());
    
    $_SESSION['message'] = "An error occurred while processing your request";
    $_SESSION['message_type'] = 'danger';
    
} finally {
    if (isset($conn)) {
        $conn->close();
    }
    
    header("Location: ../view/reportsapp.php");
    exit();
}