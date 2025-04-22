<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Initialize the $username variable to avoid the undefined variable warning
$username = '';

// Handle new user addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if the username is set before using it
    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
    } else {
        $error_message = "Username is required!";
    }

    // Validate password match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, fullname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $full_name, $email, $hashed_password]);

        $success_message = "User added successfully!";
    }
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style/manage.css">
    <style>
    body {
        background-color: #000000; /* Black background */
        font-family: Arial, sans-serif;
        color: #f39c12; /* Orange text */
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #f39c12; /* Orange text */
    }

    .BD {
        text-align: center;
        width: 30vh;
        background-color: #f39c12; /* Orange background */
        text-decoration: none;
        border-radius: 20px;
        padding: 10px;
        display: grid;
        color: #000000; /* Black text */
        font-weight: bold;
    }

    .BD:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 165, 0, 0.5);
    }

    .form-container {
        width: 40%;
        margin: 0 auto;
        padding: 20px;
        background-color: #1a1a1a; /* Dark grey-black background */
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(255, 165, 0, 0.1);
        margin-bottom: 40px;
    }

    .form-container h2 {
        text-align: center;
        color: #f39c12; /* Orange header */
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        color: #f39c12;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: none;
        outline: none;
        background-color: #2c2c2c; /* Very dark grey */
        color: #f39c12;
        border-radius: 5px;
    }

    .form-control:focus {
        background-color: #333;
        box-shadow: 0 0 5px rgba(255, 165, 0, 0.7);
    }

    button {
        width: 100%;
        padding: 12px 20px;
        background-color: #f39c12; /* Orange background */
        color: #000000; /* Black text */
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #e67e22; /* Slightly darker orange */
    }

    .error {
        background-color: #ffcccc;
        color: #e74c3c;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
    }

    .success {
        background-color: #ccffcc;
        color: #28a745;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
    }

    table {
        width: 80%;
        margin: 0 auto;
        margin-top: 30px;
        border-collapse: collapse;
        text-align: left;
    }

    table th, table td {
        padding: 12px;
        border: 1px solid #f39c12; /* Orange borders */
    }

    table th {
        background-color: #1a1a1a;
        color: #f39c12;
    }

    table td {
        background-color: #111111;
        color: #f39c12;
    }

    table tr:hover {
        background-color: #222;
    }

    .BD {
        margin-top: 40px;
        margin-bottom: 40px;
        text-align: center;
        display: grid;
        margin-left: 78vh;
    }

    a {
        color: #f39c12;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<h1>Manage Users</h1>

<?php if (isset($success_message)): ?>
    <div class="success"><?php echo $success_message; ?></div>
<?php elseif (isset($error_message)): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="form-container">
    <h2>Add New User</h2>
    <form method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit">Add User</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>" style="color: #3498db; text-decoration: none;">Edit</a>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')" style="color: #e74c3c; text-decoration: none; margin-left: 10px;">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="admin_dashboard.php" class="BD">Back to Dashboard</a>

</body>
</html>


