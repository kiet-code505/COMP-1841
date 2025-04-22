<?php
// Include database connection file
include 'database.php'; // Ensure 'database.php' initializes the $pdo object correctly.

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($email) && !empty($password)) {
        // Sanitize inputs
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        try {
            // Check if the email exists in the database
            $stmt = $pdo->prepare("SELECT id, fullname, username, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // If email exists and password is correct
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the dashboard or home page
                header("Location: dashboard.php"); // Replace with actual dashboard or home page URL
                exit;
            } else {
                $message = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style/login.css">
  <style>
    .box {
      position: relative;
      width: 380px;
      height: 580px;
      background: #ffffff; /* White background for the form box */
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
      font-family: "Poppins";
      --color: #ff9800; /* Orange accent color for consistency */
    }
    .bg {
      background: linear-gradient(135deg, rgb(58, 21, 190), #e0e0e0);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .form {
      position: absolute;
      background: #ffffff; /* White background for the form */
      z-index: 10;
      inset: 2px;
      border-radius: 8px;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      height: 100%; /* Ensure it takes the full height of the parent .box */
      color: #333; /* Darker color for text to ensure readability */
    }

    .form h2 {
      color: var(--color); /* Orange heading for contrast */
      font-weight: 600;
      text-align: center;
      letter-spacing: 0.1em;
    }

    .inputbox {
      position: relative;
      width: 300px;
      margin-top: 35px;
    }

    .inputbox input {
      position: relative;
      width: 100%;
      padding: 10px 10px;
      background: transparent;
      border: none;
      outline: none;
      font-size: 1em;
      letter-spacing: 0.05em;
      border-bottom: 2px solid #ccc; /* Light border for input fields */
      color: #333; /* Dark text for input */
    }

    .inputbox span {
      position: absolute;
      color: #888; /* Subtle label color */
      left: 0;
      padding: 20px 0 10px 0;
      font-size: 1em;
      pointer-events: none;
      letter-spacing: 0.05em;
      transition: 0.5s;
    }

    .inputbox input:valid ~ span,
    .inputbox input:focus ~ span {
      color: var(--color); /* Accent color when focused */
      transform: translateY(-40px);
      font-size: 0.75em;
    }

    .inputbox i {
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 2px;
      background: var(--color); /* Orange underline effect */
      transition: 0.5s;
      border-radius: 4px;
      pointer-events: none;
    }

    .inputbox input:valid ~ i,
    .inputbox input:focus ~ i {
      height: 2px;
    }

    .links a {
      margin: 18px 0;
      font-size: 0.9em;
      text-decoration: none;
      color: #666; /* Darker gray for links */
    }

    .links a:hover {
      color: var(--color); /* Orange on hover */
    }

    input[type="submit"] {
      width: 300px;
      background: var(--color); /* Orange background for the submit button */
      border: none;
      outline: none;
      padding: 11px 25px;
      margin-top: 10px;
      border-radius: 4px;
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      color: #fff; /* White text for contrast */
    }

    .admin-btn {
      width: 300px;
      background: transparent;
      border: 2px solid var(--color);
      outline: none;
      padding: 11px 25px;
      margin-top: 15px;
      border-radius: 4px;
      font-weight: 600;
      font-size: 15px;
      text-align: center;
      color: var(--color);
      text-decoration: none;
      display: block;
      cursor: pointer;
    }

    .admin-btn:hover {
      background: var(--color);
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="bg"></div>
  <div class="box">
    <form class="form" action="" method="POST">
    <div class="form">
  <h2 style="text-align: center; margin-bottom: 10px;">
    <span style="font-size: 1.5em; display: block;">SCHOOL</span>
    <span style="font-size: 0.9em; color: #666;">by Pham Gia Kiet</span>
  </h2>
  <h2>Login</h2>
  <?php if (!empty($message)): ?>
    <p style="color: #45f3ff; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>
      <?php if (!empty($message)): ?>
        <p style="color: #45f3ff; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <div class="inputbox">
        <input type="email" name="email" required>
        <span>Email</span>
        <i></i>
      </div>
      <div class="inputbox">
        <input type="password" name="password" required>
        <span>Password</span>
        <i></i>
      </div>
      <div class="links">
        <a href="register.php">Don't have an account? Sign Up</a>
      </div>
      <input type="submit" value="Login">
      <!-- Admin Login Button -->
      <a href="adminlogin.php" class="admin-btn">Admin Login</a>
    </form>
  </div>
</body>
</html>
