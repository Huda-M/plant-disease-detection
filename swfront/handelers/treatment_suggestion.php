<?php
session_start(); // يجب أن تكون في بداية الملف بدون أي مسافات قبلها
require '../config/db_connection.php';

$_SESSION['message'] = ""; // تهيئة متغير الرسالة

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تصحيح أسماء الحقول لتتطابق مع النموذج
    $treatment_name = $_POST['diseaseName'];
    $method = $_POST['symptoms'];
    $expert_id = $_SESSION['user']['id'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO treatment_suggestions 
            (expert_id, name, method) 
            VALUES (?, ?, ?)");
        
        $stmt->execute([
            $expert_id,
            $treatment_name,
            $method
        ]);

        $_SESSION['message'] = "تمت الإضافة بنجاح!";
        header("Location: ../view/suggesttreatment.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['message'] = "خطأ: " . $e->getMessage(); // حفظ الخطأ في الجلسة
        header("Location: ../view/suggesttreatment.php");
        exit();
    }
}
?>