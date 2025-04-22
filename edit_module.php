<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Fetch module details for editing
if (isset($_GET['id'])) {
    $module_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
    $stmt->execute([$module_id]);
    $module = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$module) {
        echo "Module not found.";
        exit();
    }
} else {
    echo "Invalid module ID.";
    exit();
}

// Handle module update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_name = trim($_POST['module_name']);

    if (empty($module_name)) {
        $error_message = "Module name cannot be empty.";
    } else {
        $stmt = $pdo->prepare("UPDATE modules SET name = ? WHERE id = ?");
        $stmt->execute([$module_name, $module_id]);

        $success_message = "Module updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Module</title>
    <link rel="stylesheet" href="style/manage.css">
    <style>
        body {
    background-color: #23242a;
    font-family: Arial, sans-serif;
}

h1 {
    text-align: center;
    color: #fff;
    margin-bottom: 30px;
}

/* Success/Error Messages */
.success, .error {
    width: 50%;
    margin: 20px auto;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
}

.success {
    background-color: #e8f5e9;
    color: #4caf50;
}

.error {
    background-color: #ffebee;
    color: #f44336;
}

/* Form Container */
form {
    width: 40%;
    margin: 0 auto;
    padding: 20px;
    background-color: #28292d;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
    color: #fff;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: none;
    outline: none;
    background-color: #3a3b3d;
    color: #fff;
    border-radius: 5px;
}

.form-control:focus {
    background-color: #000;
    box-shadow: 0 0 5px rgba(70, 243, 255, 0.5);
}

/* Submit Button */
button {
    width: 100%;
    padding: 12px 20px;
    background-color: #45f3ff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #33d1d9;
}

/* Back Button */
.BD {
    display: block;
    width: 200px;
    margin: 20px auto;
    text-align: center;
    padding: 10px;
    background-color: #45f3ff;
    color: #fff;
    text-decoration: none;
    border-radius: 20px;
    font-weight: bold;
    transition: transform 0.3s, box-shadow 0.3s;
}

.BD:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
}

    </style>
</head>
<body>
<h1>Edit Module</h1>

<!-- Display success or error message -->
<?php if (isset($success_message)): ?>
    <div class="success"><?php echo $success_message; ?></div>
<?php elseif (isset($error_message)): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="module_name">Module Name:</label>
        <input type="text" id="module_name" name="module_name" class="form-control" value="<?php echo htmlspecialchars($module['name']); ?>" required>
    </div>
    <button type="submit">Update Module</button>
</form>

<a href="manage_modules.php" class="BD">Back to Manage Modules</a>
</body>
</html>
