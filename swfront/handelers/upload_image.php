<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من وجود الملف
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Please upload a valid image!";
        header("Location: ../view/upload_page.php");
        exit;
    }

    $file = $_FILES['image'];
    $upload_dir = '../uploads/images/';
    
    // التحقق من نوع الملف
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = "Only JPG, PNG, or GIF files are allowed!";
        header("Location: ../view/upload_page.php");
        exit;
    }

    // التحقق من حجم الملف
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        $_SESSION['error'] = "File size exceeds 5MB limit!";
        header("Location: ../view/upload_page.php");
        exit;
    }

    // إنشاء اسم فريد للملف
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid($_SESSION['user_id'] . '_', true) . '.' . $extension;
    $target_path = $upload_dir . $filename;

    // إنشاء المجلد إذا لم يكن موجودًا
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // نقل الملف
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // استدعاء نموذج الذكاء الاصطناعي
        
        if ($result && isset($result['disease'])) {
            $_SESSION['diagnosis'] = [
                'disease' => $result['disease'],
                'treatment' => $result['treatment'] ?? 'No treatment available',
                'image_path' => $target_path
            ];
            header("Location: ../plant/diagnosis_result.php");
        } else {
            $_SESSION['error'] = "Failed to analyze image!";
            header("Location: ../view/upload_page.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Error uploading file!";
        header("Location: ../view/upload_page.php");
        exit;
    }
}

// دالة استدعاء API الذكاء الاصطناعي (مثال)
// function call_ai_model($image_path) {
//     $api_url = 'https://your-ai-api.com/predict';
    
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $api_url);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, [
//         'image' => new CURLFile($image_path)
//     ]);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
//     // إضافة الهيدرات إذا لزم الأمر
//     // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer YOUR_API_KEY']);
    
//     $response = curl_exec($ch);
//     curl_close($ch);
    
//     return json_decode($response, true);
// }