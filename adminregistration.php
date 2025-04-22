<?php
include 'database.php'; // Ensure this file initializes $pdo correctly

// Secure admin key (Consider storing in an environment variable)
define('ADMIN_KEY', '8929'); 

$message = ''; // Message for user feedback

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $admin_key_input = trim($_POST['admin_key']);
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Input validation
    if (empty($admin_key_input) || empty($username) || empty($email) || empty($password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($admin_key_input !== ADMIN_KEY) {
        $message = "Invalid admin key.";
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        try {
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->execute();

            $message = "Admin account created successfully!";
        } catch (PDOException $e) {
            $message = "Error: Unable to register admin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Admin Account</title>
  <link rel="stylesheet" href="style.css">
  <style>
  body {
      background-color: #000000;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: 'Arial', sans-serif;
  }

  .form-container {
      background-color: #1a1a1a;
      border-radius: 12px;
      width: 380px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
  }

  .form-container h2 {
      text-align: center;
      color: #ff6600;
      margin-bottom: 20px;
  }

  .input-group {
      margin-bottom: 15px;
  }

  .input-group label {
      display: block;
      font-size: 14px;
      color: #ff6600;
      margin-bottom: 5px;
  }

  .input-group input {
      width: 100%;
      padding: 10px;
      border: 2px solid #333;
      border-radius: 6px;
      background-color: #000;
      color: #fff;
      font-size: 14px;
  }

  .button {
      width: 100%;
      padding: 12px;
      background-color: #ff6600;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
  }

  .button:hover {
      background-color: #e65c00;
  }

  .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #bbb;
      text-decoration: none;
  }

  .back-link:hover {
      color: #ff6600;
  }

  .message {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
  }
</style>
</head>
<body>

  <div class="form-container">
    <form method="POST">
      <h2>Create Admin Account</h2>

      <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <div class="input-group">
        <label>Admin Key</label>
        <input type="text" name="admin_key" required>
      </div>

      <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" required>
      </div>

      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <button type="submit" class="button">Register</button>
      <a href="adminlogin.php" class="back-link">Back to Admin Login</a>
    </form>
  </div>

</body>
</html>



