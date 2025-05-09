<?php
session_start();
require '../config/db_connection.php';



$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $disease_name = $_POST['disease_name'];
    $symptoms = $_POST['symptoms'];
    $description = $_POST['description'];
    $expert_id = $_SESSION['user']['id'];
    
    

    try {

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
        header("Location: ../view/upload.php");
        exit();

    } catch (PDOException $e) {
        $message = "خطأ: " . $e->getMessage();
    }
}
?>