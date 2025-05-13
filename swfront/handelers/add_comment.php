<?php
session_start();
require '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    $post_id = (int)$_POST['post_id'];
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    $stmt->execute();

    header("Location: ../view/showposts.php");
    exit();
}