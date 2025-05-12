<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certificate_id = $_POST['certificate_id'] ?? null;
    $user_id = $_POST['user_id'] ?? null;

    try {
        if (isset($_POST['approve'])) {
            // Update certificate status
            $stmt = $conn->prepare("UPDATE user_certificates SET status = 'approved' WHERE id = ?");
            $stmt->bind_param("i", $certificate_id);
            $stmt->execute();
            
            // Update user role
            $stmt = $conn->prepare("UPDATE users SET role = 'expert' WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            $_SESSION['message'] = "Certificate approved and user upgraded to expert!";
            $_SESSION['message_type'] = "success";
            
        } elseif (isset($_POST['reject'])) {
            $stmt = $conn->prepare("UPDATE user_certificates SET status = 'rejected' WHERE id = ?");
            $stmt->bind_param("i", $certificate_id);
            $stmt->execute();
            
            $_SESSION['message'] = "Certificate rejected successfully!";
            $_SESSION['message_type'] = "success";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Error processing request: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    
    $conn->close();
}

header('Location: ../view/certificateapp.php');
exit();
?>