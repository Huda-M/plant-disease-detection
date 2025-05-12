<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload a Certificate</title>
    <link href="../style/uploadcertifecatestyle.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <div class="upload-container">
            <h2>Upload Your Certificate</h2>
            <form action="../handelers/upload_certificate.php" method="post" enctype="multipart/form-data">
              <label>Choose Certificate (PDF only)</label><br>
              <input class="adjust" type="file" name="certificate" accept="application/pdf" required><br>
              <button type="submit" class="btn">Upload Certificate</button>
            </form>
            <a class="a" href="index.php">back to Home</a>
          </div>
    </div>
</body>
</html>