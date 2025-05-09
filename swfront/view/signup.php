<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../style/signup.css">
</head>
<body>
    <div class="signup">
        <div class="animations">
            <div class="logo">
                <img src="../images/logos.png" alt="logoimage"/>
                <div class="logoname">
                    <h1>GreenScar</h1>
            </div>
            <h2 class="cursor typewriter-animation">Join the green community!</h2>
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
                <h4>Already have an account? <a href="login.php"><span>Log in!</span></a></h4>
                
                    <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="../auth/sigup.php" method="POST" class="form-container">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" placeholder="Enter your Name">
                    <br>
                    <br>
                    
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
                    <button type="submit">Creat an account</button>
                </form>
                
                  

            </div>

        </div>
        <div class="clear"></div>

    </div>
  </body>
</html>