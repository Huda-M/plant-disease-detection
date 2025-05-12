<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link href="../style/addpoststyle.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <h1>Add a Post</h1>
        <div id="post-container">
            <p id="no-posts">No posts yet.</p>
          </div>
          
          <div class="form-container">
            <textarea id="post-input" placeholder="Write your post here..."></textarea>
            <button id="add-post-btn">Add Post</button>
          </div>
          
          <div id="post-container"></div>
          
    </div>
    <script>
        const postInput = document.getElementById("post-input");
        const postContainer = document.getElementById("post-container");
        const addPostBtn = document.getElementById("add-post-btn");
      
        let posts = JSON.parse(localStorage.getItem("posts")) || [];
      
        function renderPosts() {
          postContainer.innerHTML = '';
          if (posts.length === 0) {
            const noPosts = document.createElement("p");
            noPosts.id = "no-posts";
            noPosts.textContent = "No posts yet.";
            postContainer.appendChild(noPosts);
            return;
          }
      
          posts.forEach((post, index) => {
            const postDiv = document.createElement("div");
            postDiv.className = "post";
      
            const content = document.createElement("p");
            content.textContent = post;
      
            const editBtn = document.createElement("button");
            editBtn.textContent = "Edit";
            editBtn.className = "edit-btn";
            editBtn.onclick = () => {
              const updatedText = prompt("Edit your post:", post);
              if (updatedText && updatedText.trim() !== "") {
                posts[index] = updatedText.trim();
                localStorage.setItem("posts", JSON.stringify(posts));
                renderPosts();
              }
            };
      
            const deleteBtn = document.createElement("button");
            deleteBtn.textContent = "Delete";
            deleteBtn.className = "delete-btn";
            deleteBtn.onclick = () => {
              posts.splice(index, 1);
              localStorage.setItem("posts", JSON.stringify(posts));
              renderPosts();
            };
      
            postDiv.appendChild(content);
            postDiv.appendChild(editBtn);
            postDiv.appendChild(deleteBtn);
            postContainer.appendChild(postDiv);
          });
        }
      
        addPostBtn.addEventListener("click", () => {
          const newPost = postInput.value.trim();
          if (newPost) {
            posts.push(newPost);
            localStorage.setItem("posts", JSON.stringify(posts));
            postInput.value = "";
            renderPosts();
          }
        });
      
        renderPosts();
      </script>
      
</body>
</html>