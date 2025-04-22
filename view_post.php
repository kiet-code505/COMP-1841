<?php
// Kết nối cơ sở dữ liệu
include 'database.php';

// Kiểm tra ID bài viết
if (!isset($_GET['id'])) {
    echo "<p>Invalid post ID!</p>";
    exit;
}

$post_id = intval($_GET['id']);

// Lấy thông tin bài viết
$post_stmt = $pdo->prepare("
    SELECT p.*, u.username, m.name AS module_name
    FROM posts p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN modules m ON p.module_id = m.id
    WHERE p.id = :post_id
");
$post_stmt->execute(['post_id' => $post_id]);
$post = $post_stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<p>Post not found!</p>";
    exit;
}

// Lấy danh sách bình luận
$comment_stmt = $pdo->prepare("
    SELECT c.*, u.username
    FROM comments c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.post_id = :post_id
    ORDER BY c.created_at DESC
");
$comment_stmt->execute(['post_id' => $post_id]);
$comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title'] ?? 'No title') ?></title>
    <link href="style/manage.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            color: #FFFFFF;
        }

        .container {
            background-color: rgba(44, 47, 51, 0.95);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 800px;
            margin: 40px;
            text-align: center;
            border: 2px solid #FFA500;
        }

        h1 {
            color: #1E90FF;
            font-weight: 700;
            margin-bottom: 20px;
        }

        p {
            color: #B0C4DE;
            font-size: 16px;
            line-height: 1.6;
        }

        .post-image {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border: 2px solid #FFA500;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1E90FF;
            color: #FFFFFF;
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #FFA500;
        }

        .comment-form {
            margin-top: 30px;
            text-align: left;
        }

        .comment-form input[type="text"],
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #3B3F45;
            border: 1px solid #1E90FF;
            color: #FFFFFF;
            border-radius: 4px;
        }

        .comment-form button {
            padding: 10px 20px;
            background-color: #1E90FF;
            border: none;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .comment-form button:hover {
            background-color: #FFA500;
        }

        .comment {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            text-align: left;
        }

        .comment strong {
            color: #FFA500;
        }

        .comment small {
            display: block;
            color: #aaa;
            font-size: 13px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Post Title: <?= htmlspecialchars($post['title']) ?></h1>
        <p><strong>Module:</strong> <?= htmlspecialchars($post['module_name']) ?></p>

        <?php if (!empty($post['image'])): ?>
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post Image" class="post-image">
        <?php else: ?>
            <p><em>No image available for this post.</em></p>
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <p><small>Posted on: <?= htmlspecialchars($post['created_at']) ?></small></p>

        <a href="manage_posts.php" class="btn">Back to Manage Posts</a>

        <!-- Display Comments -->
        <div class="comments">
            <h2>Comments</h2>
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <p><strong><?= htmlspecialchars($comment['username'] ?? 'Anonymous') ?>:</strong> <?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                        <small>At: <?= htmlspecialchars($comment['created_at']) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><em>No comments yet.</em></p>
            <?php endif; ?>
        </div>

        <!-- Comment Form -->
        <div class="comment-form">
            <h2>Leave a Comment</h2>
            <form action="submit_comment.php" method="POST">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <input type="text" name="username" placeholder="Your Name" required>
                <textarea name="comment" rows="4" placeholder="Your Comment" required></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        </div>
    </div>
</body>
</html>



