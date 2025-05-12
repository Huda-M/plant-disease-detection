<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    die(json_encode(['error' => 'يجب تسجيل الدخول أولاً']));
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// التحقق من CSRF Token
if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    die(json_encode(['error' => 'رمز الحماية غير صالح']));
}

try {
    $user_id = $_SESSION['user']['id'];
    
    switch ($data['action']) {
        case 'toggle_like':
            $post_id = (int)$data['post_id'];
            
            // التحقق من وجود الإعجاب
            $check_stmt = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
            $check_stmt->bind_param("ii", $user_id, $post_id);
            $check_stmt->execute();
            $exists = $check_stmt->get_result()->num_rows > 0;

            if ($exists) {
                // إزالة الإعجاب
                $delete_stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
                $delete_stmt->bind_param("ii", $user_id, $post_id);
                $delete_stmt->execute();
                $is_liked = false;
            } else {
                // إضافة إعجاب
                $insert_stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
                $insert_stmt->bind_param("ii", $user_id, $post_id);
                $insert_stmt->execute();
                $is_liked = true;
            }

            // حساب عدد الإعجابات الجديد
            $count_stmt = $conn->prepare("SELECT COUNT(*) AS count FROM likes WHERE post_id = ?");
            $count_stmt->bind_param("i", $post_id);
            $count_stmt->execute();
            $count = $count_stmt->get_result()->fetch_assoc()['count'];

            echo json_encode([
                'success' => true,
                'new_count' => $count,
                'is_liked' => $is_liked
            ]);
            break;

        default:
            throw new Exception("إجراء غير معروف");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}