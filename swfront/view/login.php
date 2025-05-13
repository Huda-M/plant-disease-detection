<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="../style/loginstyle.css">
</head>
<body>
    <div class="signup">
        <div class="animations">
            <div class="logo">
                <img src="../images/logos.png" alt="logoimage"/>
                <div class="logoname">
                    <h1>GreenScar</h1>
            </div>
            <h2 class="cursor typewriter-animation">Glad to see you again!</h2>
        </div>
        <div class="actual">
            <div class="logo2">
                <img src="../images/logos.png" alt="logoimage"/>
                <div class="logoname2">
                    <h1>GreenScar</h1>
            </div>
            <div class="form">
                <h3>Creat an account</h3>
                <br>
                <h4>Don't have an account? <a href="signup.php"><span>Sign up!</span></a></h4>
                
                
                   
                <?php if (isset($_SESSION['error'])): ?>
                <div class="error">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
        <form action="../auth/login.php" method="POST" class="form-container">
                    <label for="email">Email:</label>
                    <br>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                    <br>
                    <br>
                    <label for="password">Password:</label>
                    <br>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    <br>
                    <br>
                    <button type="submit">Log in</button>
                </form>
                
                  

            </div>

        </div>
        <div class="clear"></div>
</body>
