<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest a Treatment</title>
    <link href="../style/suggesttreatment.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <div class="container">
            <!-- عرض الرسائل -->
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="message"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <h2>Suggest a Treatment</h2>
        
            <!-- تصحيح method و action -->
            <form id="diseaseForm" method="POST" action="../handelers/treatment_suggestion.php">
              <label for="diseaseName">Treatment Name</label>
              <input type="text" id="diseaseName" name="diseaseName" required>
        
              <label for="symptoms">Method</label>
              <textarea id="symptoms" name="symptoms" required></textarea>
        
              <div class="button-container">
                <button type="submit" class="submit-btn">Submit Treatment</button>
              </div>
            </form>
            <a href="index.php" class="back-link">&larr; Back to Home</a>
          </div>
    </div>
</body>
</html>