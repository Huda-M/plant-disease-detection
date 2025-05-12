<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a comment</title>
    <link href="../style/commentstyle.css" rel="stylesheet" />
</head>
<body>
  <div class="parent">
    <!-- <div class="comment-container"> -->
    <form class="comment-container" method="POST" action="../handelers/create_post.php" enctype="multipart/form-data">
        <h2>Add a Comment</h2>
        
        <!-- حقل العنوان (تمت إضافته) -->
        <input class="commenttitle" type="text" name="title" placeholder="Comment Title" required>
        
        <!-- حقل النص -->
        <textarea name="content" rows="4" placeholder="Write your comment here..." required></textarea>
        
        <!-- حقل الصورة -->
        <!-- <input class="file-input" type="file" name="image" accept="image/*">
        <img id="imagePreview" class="preview" alt="Image Preview"> -->
        
        <button type="submit" class="submit-btn">Post Comment</button>
    </form>
    <!-- </div> -->
</div>
    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
    
        imageInput.addEventListener('change', function () {
          const file = this.files[0];
          if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
              imagePreview.src = e.target.result;
              imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
          } else {
            imagePreview.style.display = 'none';
          }
        });
    
        function submitComment() {
          const comment = document.getElementById('commentText').value;
          if (comment.trim() === "") {
            alert("Please write a comment.");
          } else {
            alert("Comment submitted!\n\nText: " + comment);
            document.getElementById('commentText').value = "";
            imageInput.value = "";
            imagePreview.style.display = 'none';
          }
        }
      </script>
    

</body>
</html>