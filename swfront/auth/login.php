<?php
session_start();
require '../config/db_connection.php';

// header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // التحقق من صحة البريد الإلكتروني وكلمة المرور
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // التحقق من كلمة المرور
    if ($user && password_verify($password, $user['password'])) {
        // تخزين بيانات اليوزر في الجلسة
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        $_SESSION['user_id'] = $user['id'];
        // التوجيه حسب الدور
        // if ($user['role'] === 'admin') {
            header("Location: ../view/index.php");
        // } elseif ($user['role'] === 'expert') {
        //     header("Location: ../expert/expert_home.php");
        // } else {
        //     header("Location: ../user/user_home.php");
        // }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
