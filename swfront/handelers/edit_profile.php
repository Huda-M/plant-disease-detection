<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user']['id']) || empty($_SESSION['user']['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$sql = "SELECT name, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];

    if (empty($new_name) || empty($new_email)) {
        $message = "<div style='color: red;'>Name and email cannot be empty!</div>";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div style='color: red;'>Invalid email format!</div>";
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $new_name, $new_email, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['user']['name'] = $new_name;
            $_SESSION['user']['email'] = $new_email;

                    header("Location: ../view/editprofile.php");
        } else {
            $message = "<div style='color: red;'>Error updating profile!</div>";
        }
    }
}
?>