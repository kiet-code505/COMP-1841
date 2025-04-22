<?php
session_start();

// Giả sử bạn lưu thông tin người dùng khi đăng nhập
$fullname = $_SESSION['fullname'] ?? 'Guest';
$username = $_SESSION['username'] ?? 'guest_user';

// Khởi tạo $posts để tránh lỗi nếu chưa lấy được dữ liệu
$posts = [];

// Nếu bạn có database, cần truy vấn và gán cho $posts
// Ví dụ:
require_once 'database.php'; // kết nối CSDL

$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: #fff8f0;
      color: #333;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 80%;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(255, 165, 0, 0.3);
    }

    h1 {
      color: #ff6600;
      text-align: center;
    }

    p {
      text-align: center;
    }

    .welcome h1 {
      color: #ff6600;
      margin-bottom: 10px;
    }

    .btn {
      display: inline-block;
      margin: 10px 0;
      padding: 10px 20px;
      color: #fff;
      background-color: #ff6600;
      text-decoration: none;
      border-radius: 5px;
      transition: background 0.3s, color 0.3s;
    }

    .btn:hover {
      background-color: #e65c00;
    }

    .btn-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 20px 0;
      flex-wrap: wrap;
    }

    .posts {
      margin-top: 20px;
    }

    .posts h2 {
      color: #ff6600;
    }

    .post {
      background: #fff0e6;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 5px;
      border: 1px solid #ffc299;
    }

    .post h2 a {
      color: #ff6600;
      text-decoration: none;
    }

    .post h2 a:hover {
      text-decoration: underline;
    }

    .post-meta {
      font-size: 14px;
      color: #888;
    }

    .post-actions {
      margin-top: 10px;
    }

    .post-actions a {
      margin-right: 10px;
      color: #ff6600;
      text-decoration: none;
    }

    .post-actions a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="welcome">
      <h1>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h1>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    </div>

    <div class="btn-container">
      <a href="createquestion.php" class="btn">Create New Question</a>
      <a href="edit_profile.php" class="btn">Edit Profile</a>
      <a href="login.php" class="btn">Logout</a>
      <a href="mailto:phamgiakiet1911@gmail.com" class="btn">Contact Admin</a>
    </div>

    <div class="posts">
      <h2>All Posts</h2>
      <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
          <div class="post">
            <h2><a href="viewpost.php?id=<?php echo $post['id']; ?>">
              <?php echo htmlspecialchars($post['title']); ?>
            </a></h2>
            <div class="post-meta">
              <p><strong>Posted by:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
              <p><strong>Date:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
            </div>
            <?php if ($_SESSION['username'] == $post['username']): ?>
              <div class="post-actions">
                <a href="editpost.php?id=<?php echo $post['id']; ?>">Edit</a>
                <a href="deletepost.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No posts available.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>




