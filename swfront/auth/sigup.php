<?php
session_start(); // مهم جداً عشان نستخدم $_SESSION
require '../config/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingUser = $result->fetch_assoc();

        if ($existingUser) {
            $error = "Email is already taken!";
        } else {
            $role = "user"; // نفترض إن المستخدم الجديد دوره "user"
            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
            $stmt->execute();

            // الحصول على الـ ID للمستخدم الذي تم إنشاؤه الآن
            $userId = $stmt->insert_id;

            // تخزين بيانات اليوزر في السيشن
            $_SESSION['user'] = [
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'id' => $userId // إضافة الـ ID هنا
            ];

            // توجيه المستخدم لصفحة الـ home الخاصة به
            // if ($user['role'] === 'admin') {
                header("Location: ../view/index.php");
            // } elseif ($user['role'] === 'expert') {
            //     header("Location: ../expert/expert_home.php");
            // } else {
            //     header("Location: ../user/user_home.php");
            // }
            exit();
        }
    }
}
