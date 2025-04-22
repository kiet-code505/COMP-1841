<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Handle module deletion
if (isset($_GET['id'])) {
    $module_id = $_GET['id'];

    // Check if the module exists
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
    $stmt->execute([$module_id]);
    $module = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$module) {
        echo "Module not found.";
        exit();
    }

    // Delete the module
    $stmt = $pdo->prepare("DELETE FROM modules WHERE id = ?");
    $stmt->execute([$module_id]);

    header('Location: manage_modules.php?message=Module+deleted+successfully');
    exit();
} else {
    echo "Invalid module ID.";
    exit();
}
?>