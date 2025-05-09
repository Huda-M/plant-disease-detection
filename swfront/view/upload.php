<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link href="../style/uploadstyle.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <div class="center">
            <div class="container">
                <!-- فورم تحميل الصورة -->
                <form method="POST" action="../handelers/upload.php" enctype="multipart/form-data">
                    <div class="upload-section">
                        <div class="upload-box">
                            <i class="fa fa-cloud-upload-alt upload-icon"></i>
                            <p>Drop your plant image here or click to upload</p>
                            <input type="file" id="plantImage" name="image" hidden />
                            <button type="button" onclick="document.getElementById('plantImage').click()">Upload Image</button>
                        </div>
</div>
                </form>

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
        </div>
    </div>
    <script>
      const plantInput = document.getElementById('plantImage');
      const uploadBox = document.querySelector('.upload-box');
      const predictionResult = document.getElementById('prediction-result');
    
      plantInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file && file.type.startsWith("image/")) {
          const reader = new FileReader();
          reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = "Uploaded Plant";
            img.style.maxWidth = "200px";
            img.style.marginTop = "10px";
    
            // Remove previous preview (if any)
            const oldImg = uploadBox.querySelector('img');
            if (oldImg) oldImg.remove();
    
            uploadBox.appendChild(img);
    
            // Send image to prediction API
            sendToPredictionAPI(file);
          };
          reader.readAsDataURL(file);
        } else {
          alert("Please upload a valid image file.");
        }
      });
    
      async function sendToPredictionAPI(file) {
        const formData = new FormData();
        formData.append("image", file);
    
        predictionResult.textContent = "Predicting...";
    
        try {
          const response = await fetch("?", {
            method: "POST",
            body: formData,
          });
    
          if (!response.ok) {
            throw new Error("Prediction failed");
          }
    
          const result = await response.json();
          predictionResult.textContent = `Predicted Disease: ${result.disease}`;
        } catch (err) {
          console.error(err);
          predictionResult.textContent = "Error predicting disease.";
        }
      }
    </script>
    
      
</body>
</html>