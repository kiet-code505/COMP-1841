<?php
ob_start();
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Check if the user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: manage_users.php');
    exit();
}

$user_id = $_GET['id'];
$error_message = $success_message = '';

// Fetch the user details from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: manage_users.php');
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($password) && $password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        try {
            $sql = "UPDATE users SET fullname = ?, email = ?";
            $params = [$full_name, $email];

            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password = ?";
                $params[] = $hashed_password;
            }

            $sql .= " WHERE id = ?";
            $params[] = $user_id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $success_message = "User updated successfully!";
        } catch (Exception $e) {
            $error_message = "Failed to update user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style/manage.css">
    <style>
        body {
            background-color: #111;
            color: #fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffa500;
        }

        .BD {
            text-align: center;
            width: 30vh;
            background-color: #ffa500;
            text-decoration: none;
            border-radius: 20px;
            padding: 10px;
            display: grid;
            color: black;
            font-weight: bold;
        }

        .BD:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.4);
        }

        .form-container {
            width: 40%;
            margin: 0 auto;
            padding: 20px;
            background-color: #1c1c1c;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            margin-bottom: 40px;
        }

        .form-container h2 {
            text-align: center;
            color: #ffa500;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #ffa500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            outline: none;
            background-color: #333;
            color: white;
            border-radius: 5px;
        }

        .form-control:focus {
            background-color: black;
            box-shadow: 0 0 5px rgba(255, 165, 0, 0.7);
        }

        button {
            width: 100%;
            padding: 12px 20px;
            background-color: #ffa500;
            color: black;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e69500;
        }

        .error {
            background-color: #ffebee;
            color: #f44336;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background-color: #e8f5e9;
            color: #4caf50;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .BD {
            margin-top: 40px;
            margin-bottom: 40px;
            display: grid;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <h1>Edit User</h1>

    <?php if ($success_message): ?>
        <div class="success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="form-container">
            <h2>Update User Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                </div>
                <button type="submit">Update User</button>
            </form>
        </div>
    <?php endif; ?>

    <a href="manage_users.php" class="BD">Back to Manage Users</a>
</body>
</html>


