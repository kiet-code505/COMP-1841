<?php
include 'database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch the post data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all modules
$stmtModules = $pdo->query("SELECT * FROM modules");
$modules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

// Update post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module_id = $_POST['module_id'];

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, module_id = ? WHERE id = ?");
    $stmt->execute([$title, $content, $module_id, $id]);

    header("Location: manage_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link href="style/manage.css" rel="stylesheet">
    <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #23242a;
                color: white;
                margin: 0;
                padding: 0;
            }

            .container {
                background-color: #28292d;
                border-radius: 8px;
                padding: 40px;
                width: 90%;
                max-width: 800px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                margin: 50px auto;
            }

            h1 {
                color: #45f3ff;
                text-align: center;
                font-weight: 600;
                margin-bottom: 30px;
            }

            .form-group {
                margin-bottom: 25px;
            }

            input[type="text"], textarea {
                width: 100%;
                padding: 12px;
                background-color: #3a3b3d;
                border: none;
                border-radius: 5px;
                color: white;
                font-size: 16px;
                box-sizing: border-box; /* Ensures padding doesn't affect width */
            }

            input[type="text"] {
                margin-bottom: 20px; /* Space between title and content */
            }

            textarea {
                height: 200px; /* Set height for better visibility */
                resize: vertical; /* Allows vertical resizing */
            }

            button {
                background-color: #45f3ff;
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                display: inline-block;
                margin-top: 20px;
                width: 100%;
            }

            button:hover {
                background-color: #33d1d9;
            }

            a.btn {
                display: inline-block;
                background-color: #45f3ff;
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 4px;
                margin-top: 20px;
                text-align: center;
                width: 100%;
            }

            a.btn:hover {
                background-color: #33d1d9;
            }

    </style>
</head>
<body>
<div class="container">
        <h1>Edit Post</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" class="form-control" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="module_id">Module:</label>
                <select id="module_id" name="module_id" class="form-control" required>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?= htmlspecialchars($module['id']) ?>" 
                            <?= ($module['id'] == $post['module_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($module['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn">Update Post</button>
        </form>
        <a href="manage_posts.php" class="btn">Back</a>
    </div>
</body>
</html>
