<?php
session_start();
require '../config/db_connection.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user'])) {
    header("Location: ../view/login.php");
    exit();
}

$_SESSION['message'] = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تصحيح أسماء الحقول لتتطابق مع النموذج
    $disease_name = $_POST['diseaseName'];
    $symptoms = $_POST['symptoms'];
    $description = $_POST['description'];
    $expert_id = $_SESSION['user']['id'];
    
    try {
        // التحقق من البيانات الفارغة
        if (empty($disease_name) || empty($symptoms) || empty($description)) {
            throw new Exception("جميع الحقول مطلوبة!");
        }

        // إدخال اقتراح المرض
        $stmt = $conn->prepare("INSERT INTO disease_suggestions 
            (expert_id, name, symptoms, description) 
            VALUES (?, ?, ?, ?)");
        
        $stmt->execute([
            $expert_id,
            $disease_name,
            $symptoms,
            $description
        ]);

        $_SESSION['message'] = "تمت الإضافة بنجاح!";
        header("Location: ../view/suggestdisease.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = "خطأ: " . $e->getMessage();
        header("Location: ../view/suggestdisease.php");
        exit();
    }
}
?>