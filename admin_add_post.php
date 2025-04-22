<?php
// Include your database connection
include 'database.php';

// Fetch all modules for the dropdown
$modules_stmt = $pdo->query("SELECT id, name FROM modules");
$modules = $modules_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Post</title>
    <link href="style/manage.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1e2f, #3a3a5a);
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(40, 41, 45, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #45f3ff;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, select, textarea {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #2b2c30;
            border: 1px solid #45f3ff;
            color: #fff;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #45f3ff;
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #33d1d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Post</h1>
        <form action="process_add_post.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea name="content" rows="5" placeholder="Post Content" required></textarea>
            <select name="module_id" required>
                <option value="">Select Module</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?php echo $module['id']; ?>">
                        <?php echo htmlspecialchars($module['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Post</button>
        </form>
    </div>
</body>
</html>
