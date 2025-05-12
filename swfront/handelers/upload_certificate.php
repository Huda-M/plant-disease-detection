<?php
session_start();
require '../config/db_connection.php';

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])) {
    echo "You need to be logged in to upload a certificate.";
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === 0) {
        $file = $_FILES['certificate'];
        $allowed_types = ['application/pdf'];

        if (!in_array($file['type'], $allowed_types)) {
            echo "Only PDF files are allowed!";
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            echo "File size should not exceed 5MB!";
            exit;
        }

        // إنشاء مسار الحفظ
        $uploads_dir = '../uploads/certificates';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        $newFileName = uniqid('cert_', true) . '.pdf';
        $file_path = $uploads_dir . '/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // حفظ مسار الملف في قاعدة البيانات (نستخدم relative path)
            $relative_path = 'uploads/certificates/' . $newFileName;

            $stmt = $conn->prepare("INSERT INTO user_certificates (user_id, file_url, submitted_at, status) VALUES (?, ?, NOW(), 'pending')");
            $stmt->bind_param("ss", $user_id, $relative_path);

            if ($stmt->execute()) {
                header("Location: ../view/uploadcertificate.php");
                exit();
            } else {
                echo "Database error: " . $stmt->error;
            }
        } else {
            echo "Error moving the file!";
        }
    } else {
        echo "Please select a valid certificate file.";
    }
}
?>