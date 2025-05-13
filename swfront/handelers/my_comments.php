<?php
session_start();

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user = $_SESSION['user'];

require '../config/db_connection.php';

// حذف تعليق
if (isset($_GET['delete'])) {
    $comment_id = $_GET['delete'];

    $sql = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $comment_id, $user['id']);
    $stmt->execute();

    header("Location: my_comments.php");
    exit();
}

// تعديل تعليق
if (isset($_POST['update_comment'])) {
    $comment_id = $_POST['comment_id'];
    $updated_content = $_POST['updated_content'];

    $sql = "UPDATE comments SET content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $updated_content, $comment_id, $user['id']);
    $stmt->execute();

    header("Location: ../view/my_comments.php");
    exit();
}
?>
