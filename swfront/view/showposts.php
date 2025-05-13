<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Posts</title>
  <style>
    /* إضافة استايلات جديدة بدل الملف الخارجي */
    .parent {
      max-width: 800px;
      margin: 2rem auto;
      padding: 20px;
      background-color: #f0f2f5;
    }

    .profile-container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .post-title {
      font-size: 24px;
      font-weight: bold;
      color: #244d3c;
      margin-bottom: 15px;
    }

    .post-content p {
      font-size: 16px;
      line-height: 1.6;
      color: #333;
    }

    .post-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .view-author, .report {
      color: #244d3c;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .view-author:hover, .report:hover {
      background: #f0f2f5;
    }

    .comment-section {
      margin-top: 25px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }

    .trans {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      resize: vertical;
    }

    .submit-btn, .show-comments {
      background: #244d3c;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      margin: 5px 0;
    }

    .submit-btn:hover, .show-comments:hover {
      background: #1a3a2d;
    }

    /* الاستايلات الأصلية */
    .comment {
      background-color: #244d3c;
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }

    .delete-btn {
      background-color: #ff4d4d;
      border: none;
      color: white;
      padding: 5px 10px;
      font-size: 12px;
      border-radius: 4px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #e60000;
    }
  </style>
</head>
<body>
  <div class="parent">
    <div class="profile-container">
      <!-- محتوى البوست -->
      <div class="post-title">First Post</div>
      <div class="post-content">
        <p>✨ The Rafflesia arnoldii is the world’s largest flower—it can grow up to 3 feet wide and smells like rotten meat to attract flies! 🪰🌸🌸</p>
      </div>
      
      <!-- أزرار التفاعل -->
      <div class="post-actions">
        <a href="#" class="view-author">👤 View Author</a>
        <a href="report.html" class="report">🚩 Report</a>
      </div>

      <!-- قسم التعليقات -->
      <div class="comment-section">
        <label><strong>Add a Comment:</strong></label>
        <textarea id="commentInput" class="trans" placeholder="Write your comment here..."></textarea>
        <button class="submit-btn" onclick="submitComment()">Submit Comment</button>

        <button class="show-comments" onclick="toggleComments()">👁 Show Comments</button>
        <div class="comments-list" id="commentList">
          <!-- التعليقات تظهر هنا -->
        </div>
      </div>
    </div>
  </div>

  <script>
    // نفس السكريبت السابق بدون تغيير
    function submitComment() {
      const input = document.getElementById("commentInput");
      const commentText = input.value.trim();
      const commentList = document.getElementById("commentList");

      if (commentText !== "") {
        const commentDiv = createCommentElement(commentText);
        commentList.appendChild(commentDiv);
        input.value = "";
        commentList.style.display = "block";
      }
    }

    function toggleComments() {
      const commentList = document.getElementById("commentList");
      const showBtn = document.querySelector(".show-comments");

      if (commentList.style.display === "none" || commentList.style.display === "") {
        commentList.style.display = "block";
        showBtn.textContent = "🙈 Hide Comments";
      } else {
        commentList.style.display = "none";
        showBtn.textContent = "👁 Show Comments";
      }
    }

    function createCommentElement(text) {
      const commentDiv = document.createElement("div");
      commentDiv.className = "comment";

      const commentText = document.createElement("span");
      commentText.textContent = text;

      const deleteBtn = document.createElement("button");
      deleteBtn.className = "delete-btn";
      deleteBtn.textContent = "Delete";
      deleteBtn.onclick = function () {
        commentDiv.remove();
      };

      commentDiv.appendChild(commentText);
      commentDiv.appendChild(deleteBtn);
      return commentDiv;
    }
  </script>
</body>
</html>

