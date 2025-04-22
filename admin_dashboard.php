<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if the admin is not logged in
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="style/admin.css" rel="stylesheet">
    <style>
        body {
            background-color: #000; /* Đen tuyền */
            color: #ff9900; /* Cam sáng */
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #ff6600;
            margin-top: 20px;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 40px 0;
        }

        .dashboard-box {
            background-color: #1a1a1a; /* Đen nhạt hơn để tạo khối */
            border: 2px solid #ff9900;
            border-radius: 10px;
            padding: 20px;
            margin: 15px;
            width: 200px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(255, 153, 0, 0.5);
            transition: background-color 0.3s, transform 0.3s;
        }

        .dashboard-box:hover {
            background-color: #333; /* Đen sáng hơn khi hover */
            transform: scale(1.05);
        }

        .dashboard-box h2 {
            color: #ff9900;
        }

        .dashboard-box a {
            color:rgb(11, 11, 10);
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .dashboard-box a:hover {
            color: #ff6600;
        }

        .logout-btn {
            background-color: #ff6600;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #cc5200;
        }
    </style>
</head>
<body>
    <h1>Welcome to Admin Dashboard</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>

    <div class="dashboard-container">
        <div class="dashboard-box">
            <h2>Manage Posts</h2>
            <a href="manage_posts.php">View and Manage Posts</a>
        </div>
        <div class="dashboard-box">
            <h2>Manage Users</h2>
            <a href="manage_users.php">View and Manage Users</a>
        </div>
        <div class="dashboard-box">
            <h2>Manage Modules</h2>
            <a href="manage_modules.php">View and Manage Modules</a>
        </div>
    </div>

    <a href="login.php" class="logout-btn">Logout</a>
</body>
</html>


