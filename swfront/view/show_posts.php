<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$current_user_id = $_SESSION['user']['id'];
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// توليد CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// جلب جميع المنشورات مع معلومات المستخدم
try {
    $stmt = $conn->prepare("
        SELECT 
            posts.*,
            users.name,
            COUNT(likes.id) AS likes_count,
            EXISTS(SELECT 1 FROM likes WHERE post_id = posts.id AND user_id = ?) AS is_liked
        FROM posts
        INNER JOIN users ON posts.user_id = users.id
        LEFT JOIN likes ON posts.id = likes.post_id
        WHERE posts.user_id != ?
        GROUP BY posts.id
        ORDER BY posts.created_at DESC
    ");
    
    $stmt->bind_param("ii", $current_user_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنشورات العامة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* التنسيقات تبقى كما هي */
    </style>
</head>
<body>
    <div class="container">
        <h1>المنشورات العامة</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p>لا يوجد منشورات لعرضها</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <img src="../uploads/profiles/<?= htmlspecialchars($post['profile_picture']) ?>" 
                             class="user-avatar" 
                             alt="صورة <?= htmlspecialchars($post['name']) ?>">
                        <div>
                            <h3><?= htmlspecialchars($post['name']) ?></h3>
                            <small><?= date('Y/m/d H:i', strtotime($post['created_at'])) ?></small>
                        </div>
                    </div>

                    <div class="post-content">
                        <h4><?= htmlspecialchars($post['title']) ?></h4>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    </div>

                    <div class="post-actions">
                        <button class="like-btn" 
                                data-post="<?= $post['id'] ?>" 
                                data-liked="<?= $post['is_liked'] ?>">
                            <i class="fas fa-heart <?= $post['is_liked'] ? 'liked' : '' ?>"></i>
                            <span class="like-count"><?= $post['likes_count'] ?></span>
                        </button>
                        <button class="comment-btn">
                            <i class="fas fa-comment"></i>
                            تعليق
                        </button>
                    </div>

                    <!-- قسم التعليقات يبقى كما هو -->
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
    // الإعجابات
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const postId = this.dataset.post;
            const isLiked = this.dataset.liked === '1';
            
            try {
                const response = await fetch('post_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'toggle_like',
                        post_id: postId,
                        csrf_token: '<?= $_SESSION['csrf_token'] ?>'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    const icon = this.querySelector('i');
                    const count = this.querySelector('.like-count');
                    
                    icon.classList.toggle('liked', result.is_liked);
                    count.textContent = result.new_count;
                    this.dataset.liked = result.is_liked ? '1' : '0';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('حدث خطأ أثناء معالجة الإعجاب');
            }
        });
    });
    </script>
</body>
</html>