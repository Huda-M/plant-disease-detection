<?php
session_start();
require '../config/db_connection.php'; // تأكد من المسار الصحيح

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user']['id'];
    $imagePath = null;


    // إدخال البيانات في قاعدة البيانات
    $sql = "INSERT INTO posts (user_id, title, content, created_at) 
            VALUES (?, ?, ?, NOW())";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $content);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Comment added successfully!";
    } else {
        $_SESSION['error'] = "Error adding comment: " . $conn->error;
    }

    header("Location: ../view/comment.php"); // العودة لصفحة التعليقات
    exit();
}
?>