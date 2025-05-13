<?php
session_start();

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user = $_SESSION['user'];

require '../config/db_connection.php';

// جلب الكومنتات الخاصة بالمستخدم
$sql = "SELECT comments.*, posts.title AS post_title, posts.content AS post_content 
        FROM comments 
        JOIN posts ON comments.post_id = posts.id 
        WHERE comments.user_id = ? 
        ORDER BY comments.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Comments</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .parent {
            background-image: linear-gradient(rgba(6, 23, 16, 0.799), rgba(0, 0, 0, 0.7)), url(../images/bgfile.jpeg);
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            width: 100vw;
        }

        .container {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            color: #fff;
            margin-top: 20px;
        }

        .comment-block {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .buttons a, .buttons form {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #1c3b2c;
            color: #ccc;
            border-radius: 5px;
        }

        .buttons a:hover, .buttons form:hover {
            background-color: #26523d;
        }

        .btn {
            background-color: #1c3b2c;
            border: none;
            padding: 10px 20px;
            margin: 12px 0;
            color: #ccc;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            width: 90%;
        }

        .btn:hover {
            background-color: #26523d;
        }

        .back-link {
            display: block;
            color: #ccc;
            text-decoration: none;
            margin-top: 10px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        textarea {
            width: 90%;
            padding: 8px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            resize: vertical;
        }
    </style>
</head>
<body class="parent">

<div class="container">
    <h2>My Comments</h2>

    <?php if (count($comments) === 0): ?>
        <p>You haven't made any comments yet.</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment-block">
                <div class="post">
                    <h4><?= htmlspecialchars($comment['post_title']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($comment['post_content'])) ?></p>
                </div>

                <?php if (isset($_GET['edit']) && $_GET['edit'] == $comment['id']): ?>
                    <!-- Form for editing -->
                    <form method="POST" action="../handelers/my_comments.php">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <textarea name="updated_content" rows="4" cols="50"><?= htmlspecialchars($comment['content']) ?></textarea><br>
                        <button type="submit" name="update_comment" class="btn">Save</button>
                        <a href="my_comments.php" class="back-link">Cancel</a>
                    </form>
                <?php else: ?>
                    <!-- Normal comment view -->
                    <div class="comment">
                        <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        <small>Commented on: <?= htmlspecialchars($comment['created_at']) ?></small>
                    </div>
                    <div class="buttons">
                        <a href="my_comments.php?edit=<?= $comment['id'] ?>" class="btn">Edit</a>
                        <a href="../handelers/my_comments.php?delete=<?= $comment['id'] ?>" onclick="return confirm('Are you sure you want to delete this comment?');" class="btn">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
