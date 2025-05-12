<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest a Disease</title>
    <link href="../style/suggestdisease.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <div class="container">
            <!-- عرض الرسائل -->
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="message"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <h2>Suggest a Disease</h2>
        
            <!-- تصحيح method و action -->
            <form id="diseaseForm" method="POST" action="../handelers/disease_suggestions.php">
              <label for="diseaseName">Disease Name</label>
              <input type="text" id="diseaseName" name="diseaseName" required>

              <label for="symptoms">Symptoms</label>
              <textarea id="symptoms" name="symptoms" required></textarea>

              <label for="description">Description</label>
              <textarea id="description" name="description" required></textarea>

              <div class="button-container">
                <button type="submit" class="submit-btn">Submit Disease</button>
              </div>
            </form>
            <a href="index.php" class="back-link">&larr; Back to Home</a>
          </div>
    </div>
    <script>
        // إزالة preventDefault للسماح بالإرسال الطبيعي
        document.getElementById('diseaseForm').addEventListener('submit', function (e) {
            alert("Disease submitted successfully!");
        });
    </script>
</body>
</html>