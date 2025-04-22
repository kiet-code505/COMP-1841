<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

$fullname = $_SESSION['fullname'];  // Retrieve the user's full name from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #23242a;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
        h1 {
            color: #45f3ff;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #45f3ff;
            border: 2px solid #45f3ff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
            margin-right: 10px; /* Space between buttons */
        }
        a:hover {
            background: #45f3ff;
            color: #1c1c1c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h1>
        <p>You have successfully logged in.</p>
        <a href="dashboard.php">Go to Dashboard</a> <!-- Dashboard Link -->
        <a href="login.php">Logout</a> <!-- Logout Link -->
    </div>
</body>
</html>


