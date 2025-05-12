<?php
session_start();
require '../config/db_connection.php';

// توليد CSRF Token إذا لم يكن موجود
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// تحميل رسائل الأخطاء والنجاح من الجلسة
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? false;
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="../style/changepassword.css" rel="stylesheet">
</head>
<body>
    <div class="parent">
        <div class="form-container">
            <h2>Change Password</h2>
            
            <!-- عرض رسائل الأخطاء -->
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- عرض رسالة النجاح -->
            <?php if ($success): ?>
                <div class="success-box">
                    Password changed successfully!
                </div>
            <?php endif; ?>

            <form action="../handelers/change_password.php" method="post">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <input type="password" name="current_password" placeholder="Current Password" required><br>
                <input type="password" name="new_password" placeholder="New Password" required><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
                <button type="submit" class="btn">Change Password</button>
            </form>
            <a class="a" href="index.php">Back to Home</a>
        </div>
    </div>
</body>
</html>