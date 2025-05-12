<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add a Disease</title>
    <link href="../style/adddiseasestyle.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
    <div class="form-section">
                  <h2>Add New Disease</h2>
                  <form method="POST" enctype="multipart/form-data" action="../handelers/disease_suggestions.php">
                    <input type="text" placeholder="Disease Name" required name="disease_name" />
                    <textarea placeholder="Description" rows="3" required name="description"></textarea>
                    <!-- <input type="text" name="plant_name" placeholder="Plant Name (e.g., Tomato, Potato)" required /> -->
                    <textarea placeholder="Symptoms" rows="2" required name="symptoms"></textarea>
                    <!-- <input class="choosefile" type="file" /> -->
                    <button type="submit">Add Disease</button>
                  </form>
                </div>
    </div>
</body>
</html>