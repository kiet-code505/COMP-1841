<?php
// Include database connection
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = intval($_POST['post_id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if (empty($post_id) || empty($username) || empty($comment)) {
        die("Error: All fields are required.");
    }

    try {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Error: User not found.");
        }

        $user_id = $user['id'];

        // Use the correct column name for the comment text (e.g., 'comment')
        $stmt = $pdo->prepare("
            INSERT INTO comments (post_id, user_id, comment, created_at)
            VALUES (:post_id, :user_id, :comment, NOW())
        ");
        $stmt->execute([
            'post_id' => $post_id,
            'user_id' => $user_id,
            'comment' => $comment
        ]);

        header("Location: view_post.php?id=$post_id");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
