<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $module_id = intval($_POST['module_id']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_dir = 'uploads/';

        // Ensure uploads directory exists
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }

        $image_path = $image_dir . $image_name;

        // Move the uploaded file
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Insert post into the database
            $stmt = $pdo->prepare("
                INSERT INTO posts (title, content, module_id, image_path, created_at)
                VALUES (:title, :content, :module_id, :image_path, NOW())
            ");

            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'module_id' => $module_id,
                'image_path' => $image_path
            ]);

            echo "<p>Post added successfully!</p>";
            echo '<a href="manage_posts.php">Back to Manage Posts</a>';
        } else {
            echo "<p>Error uploading the image.</p>";
        }
    } else {
        echo "<p>Image upload failed. Error code: {$_FILES['image']['error']}</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
