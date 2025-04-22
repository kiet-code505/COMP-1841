<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Post ID not specified.");
}

$post_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post || $post['username'] !== $_SESSION['username']) {
        die("You are not authorized to edit this post.");
    }
} catch (PDOException $e) {
    die("Error fetching post: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            die("Error updating post: " . $e->getMessage());
        }
    } else {
        $message = "Title and content cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff5e6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        h1 {
            color: #ff6600;
            margin-bottom: 20px;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            color: #ff6600;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #e65c00;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #ff6600;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>

        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>

            <button type="submit">Update Post</button>
        </form>

        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>



