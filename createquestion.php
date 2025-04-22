<?php
session_start();
include 'database.php'; // Ensure this connects to your database using PDO.

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $module_id = $_POST['module_id'];

    if (!empty($title) && !empty($content) && !empty($module_id)) {
        // Handle image upload
        $image_path = '';
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "uploads/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $image_name = basename($_FILES["image"]["name"]);
            $image_name = preg_replace("/[^a-zA-Z0-9\-_\.]/", "_", $image_name);
            $target_file = $target_dir . time() . '_' . $image_name;

            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                } else {
                    $message = "Image upload failed.";
                }
            } else {
                switch ($_FILES['image']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $message = "The uploaded file exceeds the allowed size.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $message = "The uploaded file was only partially uploaded.";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $message = "No file was uploaded.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $message = "Missing a temporary folder.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $message = "Failed to write file to disk.";
                        break;
                    default:
                        $message = "Unknown file upload error.";
                }
            }
        }

        if (!isset($_SESSION['username'])) {
            $message = "User not logged in.";
            exit;
        }

        $username = $_SESSION['username'];

        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, module, image, username) VALUES (:title, :content, :module, :image, :username)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':module', $module_id);
            $stmt->bindParam(':image', $image_path);
            $stmt->bindParam(':username', $username);

            if ($stmt->execute()) {
                $message = "Question created successfully!";
            } else {
                $message = "Something went wrong.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Question</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #ff6600;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        input[type="text"], textarea, select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            color: #333;
        }

        button {
            background-color: #ff6600;
            color: #ffffff;
            border: none;
            padding: 10px 25px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #cc5200;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #ff6600;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        p.message {
            color: #cc5200;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Create a New Question</h1>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="content">Content:</label>
        <textarea name="content" id="content" required></textarea>

        <label for="module_id">Module:</label>
        <select name="module_id" id="module" required>
            <option value="1">GENERAL</option>
            <option value="14">HTML</option>
            <option value="12">JAVA</option>
            <option value="15">Space</option>
        </select>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image">

        <button type="submit">Create Question</button>
    </form>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>
</body>
</html>



