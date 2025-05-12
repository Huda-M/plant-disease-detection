<?php
session_start();
require '../config/db_connection.php';

// التحقق من صلاحيات المدير
if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/unauthorized.php");
    exit();
}

// توليد CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// جلب بيانات المستخدمين
$users = [];
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
} else {
    die("Error fetching users: " . $conn->error);
}

// عرض الرسائل
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="../style/usermanage.css" rel="stylesheet">
</head>
<body>
    <div class="parent">
        <div class="header">
            <a href="index.php" class="home-link">← Back to Home</a>
        </div>

        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <div class="container">
            <h2>Manage Users</h2>
            
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= strtoupper($user['role']) ?></td>
                            <td>
                                <a href="../handelers/user_management.php?action=delete&id=<?= $user['id'] ?>&token=<?= $_SESSION['csrf_token'] ?>" 
                                   class="delete-btn"
                                   onclick="return confirm('Are you sure?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>