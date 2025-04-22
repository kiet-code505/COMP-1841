<?php
session_start();
include 'database.php'; // Ensure database connection

if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in.");
}

$user_id = $_SESSION['user_id']; // Fetch logged-in user's ID

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $fullname = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($fullname) || empty($email)) {
        $message = "Please fill in all required fields.";
    } else {
        try {
            // Check if email is already in use by another user
            $emailCheck = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
            $emailCheck->execute(['email' => $email, 'id' => $user_id]);

            if ($emailCheck->rowCount() > 0) {
                $message = "Email is already in use by another account.";
            } else {
                // Handle password change
                if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                    if (password_verify($current_password, $user['password'])) {
                        if ($new_password === $confirm_password) {
                            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email, password = :new_password WHERE id = :id");
                            $stmt->bindParam(':new_password', $new_hashed_password);
                        } else {
                            $message = "New passwords do not match.";
                        }
                    } else {
                        $message = "Current password is incorrect.";
                    }
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email WHERE id = :id");
                }

                if (empty($message)) {
                    $stmt->bindParam(':fullname', $fullname);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':id', $user_id);
                    $stmt->execute();
                    $message = "Profile updated successfully!";
                }
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    body {
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #ff7f50, #ff4500); /* Orange gradient background */
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background: #fff; /* White background for the container */
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    width: 500px;
    text-align: center;
}

h1 {
    color: #ff4500; /* Dark orange for the header */
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-top: 10px;
    font-size: 14px;
    color: #555; /* Dark gray for labels */
}

input[type="text"], 
input[type="email"], 
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    background-color: #f0f0f0; /* Light gray for input fields */
    color: #333; /* Dark gray for text */
    border: 1px solid #ccc; /* Light gray border */
}

button {
    background-color: #ff4500; /* Orange for the button */
    color: #fff;
    border: none;
    padding: 10px 20px;
    margin-top: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #ff7f50; /* Lighter orange on hover */
}

.back-link {
    margin-top: 20px;
    display: inline-block;
    color: #ff4500; /* Dark orange for back link */
    text-decoration: none;
    font-size: 16px;
}

.message {
    margin-top: 10px;
    color: #ff4500; /* Dark orange for messages */
}
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Profile</h1>
        
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" value="<?php echo isset($user['fullname']) ? htmlspecialchars($user['fullname']) : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="password">Current Password:</label>
            <input type="password" name="password">

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password">

            <button type="submit" name="update">Update Profile</button>
        </form>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>

</body>
</html>


