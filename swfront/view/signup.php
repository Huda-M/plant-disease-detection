<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - GreenScar</title>
    <link rel="stylesheet" href="../style/signup_style.css">
</head>
<body>
    <div class="signup-container">
        <div class="logo-section">
            <img src="../images/logo.png" alt="Logo">
            <h1>GreenScar</h1>
        </div>

        <div class="form-section">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../auth/signup.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="signup-btn">Create Account</button>
            </form>

            <div class="links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>