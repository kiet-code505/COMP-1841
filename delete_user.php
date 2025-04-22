<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']); // Ensure the ID is an integer

    // Prepare the statement to delete the user
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // Redirect back to manage_users.php with a success message
    header('Location: manage_users.php?message=User deleted successfully');
    exit();
} else {
    // Redirect back if no user ID is provided
    header('Location: manage_users.php?error=Invalid user ID');
    exit();
}
