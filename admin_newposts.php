<?php
// Include your database connection (use PDO or your preferred method)
include 'database.php';

session_start();

// Ensure the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['admin_id'];

// Get the username from the database based on the user_id
$stmt = $pdo->prepare("SELECT username FROM admins WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    $username = $user['username'];
} else {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $module_id = (int) $_POST['module_id'];
    $title = htmlspecialchars($_POST['title']); // Prevent XSS
    $content = htmlspecialchars($_POST['content']); // Prevent XSS
    
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }
        
        $image_path = 'uploads/' . uniqid() . '.' . $file_extension; // Prevent filename collisions
        move_uploaded_file($image_tmp_name, $image_path);
        $image = $image_path;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, username, module_id, title, content, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $username, $module_id, $title, $content, $image]);
        header("Location: manage_posts.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&amp;display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1c1c2e;
            margin: 0;
            padding: 0;
            display: grid;
            place-content: center;
            height: 100vh;
            color: #f5f5f5;
        }
        .container {
            background-color: #2e2f4b;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }
        h1 {
            color: #ff7f50;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            color: #ffdab9;
            font-weight: 500;
        }
        .form-control, .form-control-file {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #454667;
            color: #fff;
            border: 1px solid #6c5ce7;
            border-radius: 6px;
            margin-top: 8px;
            outline: none;
        }
        .form-control:focus, .form-control-file:focus {
            border-color: #ff7f50;
            box-shadow: 0 0 8px rgba(255, 127, 80, 0.6);
        }
        textarea.form-control {
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 12px 20px;
            background-color: #6c5ce7;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #4834d4;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff7f50;
            color: #fff;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #ff6347;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add New Post</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="module_id">Module:</label>
            <select id="module_id" name="module_id" class="form-control" required>
                <option value="1">General</option>
                <option value="2">HTML</option>
                <option value="3">Java</option>
                <option value="4">Space</option>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>
        <button type="submit" class="btn">Add Post</button>
    </form>
    <br>
    <a href="manage_posts.php" class="btn">Back to Manage Posts</a>
</div>
</body>
</html>

