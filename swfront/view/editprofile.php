<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="../style/editprofile.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <div class="form-container">
            <h2>Edit Profile</h2>
            <form action="../handelers/edit_profile.php" method="post">
              <input type="text" name="name" placeholder="Full Name"  required><br>
              <input type="email" name="email" placeholder="Email"  required><br>
              <button type="submit" class="btn">Save Changes</button>
            </form>
            <a href="index.php" class="back-link">&larr; Back to Home</a>
          </div>
    </div>
</body>
</html>