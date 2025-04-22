<?php
session_start();
include 'database.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Post ID not specified.");
}

$post_id = $_GET['id'];

// Fetch post from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post || $post['username'] !== $_SESSION['username']) {
        die("You are not authorized to delete this post.");
    }
} catch (PDOException $e) {
    die("Error fetching post: " . $e->getMessage());
}

// Delete post
try {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: dashboard.php"); // Redirect after deletion
    exit();
} catch (PDOException $e) {
    die("Error deleting post: " . $e->getMessage());
}
?>
