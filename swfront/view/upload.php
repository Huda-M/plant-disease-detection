<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tumor Detection System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: linear-gradient(rgba(6, 23, 16, 0.799), rgba(0, 0, 0, 0.7)), url(../images/bgfile.jpeg);
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            width: 100vw;
        }

        .container {
            max-width: 600px; 
            margin: 0 auto; 
        }
        .custom-heading {
           color: #ccc !important;
        }



        .card {
            border-radius: 15px;
        }

        #results img {
            max-height: 400px;
            margin-top: 15px;
        }

        .text-center {
            text-align: center;
        }
        .btn-primary {
         background-color: #083813; /* Green as an example */
            border-color: #083813;
        }

        .btn-primary:hover {
        background-color: #218838;
        border-color: #218838;
        
        }       

        .btn {
            width: 100%;
        }

        .lead {
            margin-bottom: 30px; 
            
        }
        .form-label{
          color: #ccc;
        }
        .card.shadow {
        background: rgba(255, 255, 255, 0.1); /* translucent white */
        backdrop-filter: blur(10px);         /* optional glassmorphism */
        border: 1px solid rgba(255, 255, 255, 0.2); 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); 
        }
        .card-text {
          color: #ccc;
        }

    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-4 custom-heading">Plant Diseasses Detection System</h1>
            <p class="lead custom-heading">Upload an Plant Leaf image to detect if there is a disease and its type.</p>
        </div>
        <!-- Upload -->
        <div class="card shadow p-4 mt-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file" class="form-label">Select Leaf Image:</label>
                    <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload and Detect</button>
            </form>
        </div>

        {% if result %}
        <!-- Display Results -->
        <div id="results" class="mt-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title text-success">{{ result }}</h4>
                    <p class="card-text text-muted">Confidence: {{ confidence }}</p>
                    <img src="{{ file_path }}" class="img-fluid rounded" alt="Uploaded Image">
                </div>
            </div>
        </div>
        {% endif %}
    </div>

  <script>
    const plantInput = document.getElementById('plantImage');
    const uploadBox = document.querySelector('.upload-box');
    const predictionBox = document.getElementById('prediction-box');
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

          const oldImg = uploadBox.querySelector('img');
          if (oldImg) oldImg.remove();

          uploadBox.appendChild(img);

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

      predictionBox.style.display = 'block';
      predictionResult.textContent = "Predicting...";

      try {
        const response = await fetch("http://127.0.0.1:5000", {
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
