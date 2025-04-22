<?php
// Include database connection file
include 'database.php'; // Ensure 'database.php' initializes the $pdo object correctly.

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($fullname) && !empty($username) && !empty($email) && !empty($password)) {
        // Sanitize inputs
        $fullname = htmlspecialchars($fullname);
        $username = htmlspecialchars($username);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
          // Check if the email already exists
          $checkEmailStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
          $checkEmailStmt->bindParam(':email', $email);
          $checkEmailStmt->execute();
      
          if ($checkEmailStmt->fetchColumn() > 0) {
              $message = "This email is already registered. Please use a different email.";
          } else {
              // Proceed with the insertion if the email does not exist
              $stmt = $pdo->prepare("INSERT INTO users (fullname, username, email, password) VALUES (:fullname, :username, :email, :password)");
              $stmt->bindParam(':fullname', $fullname);
              $stmt->bindParam(':username', $username);
              $stmt->bindParam(':email', $email);
              $stmt->bindParam(':password', $hashed_password);
      
              if ($stmt->execute()) {
                  $message = "Registration successful!";
              } else {
                  $message = "Something went wrong. Please try again.";
              }
          }
      } catch (PDOException $e) {
          $message = "Error: " . $e->getMessage();
      }
        }
    } else {
        $message = "Please fill in all fields.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Form</title>
  <link rel="stylesheet" href="style/login.css">
  <style>
.box {
  position: relative;
  width: 380px;
  height: 600px; /* Increased height to lengthen the form */
  background: #ffffff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  font-family: "Poppins";
  --color: #ff9800;
}

.form {
  position: absolute;
  background: #ffffff;
  z-index: 10;
  inset: 2px;
  border-radius: 8px;
  padding: 60px 40px; /* Increased top and bottom padding */
  display: flex;
  flex-direction: column;
  height: 100%;
  color: #333;
}

.inputbox {
  position: relative;
  width: 300px;
  margin-top: 45px; /* Increased spacing between input fields */
}

input[type="submit"] {
  width: 300px;
  background: var(--color);
  border: none;
  outline: none;
  padding: 15px 25px; /* Increased padding for a larger button */
  margin-top: 20px;   /* Increased spacing above the submit button */
  border-radius: 4px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  color: #fff;
}

.admin-btn {
  width: 300px;
  background: transparent;
  border: 2px solid var(--color);
  outline: none;
  padding: 15px 25px; /* Increased padding for a larger button */
  margin-top: 25px;   /* Increased spacing above the admin button */
  border-radius: 4px;
  font-weight: 600;
  font-size: 16px;
  text-align: center;
  color: var(--color);
  text-decoration: none;
  display: block;
  cursor: pointer;
}

  </style>
</head>
<body>
  <div class="box">
    <form class="form" action="" method="POST">
      <h2>Sign Up</h2>
      <?php if (!empty($message)): ?>
        <p style="color: #45f3ff; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <div class="inputbox">
        <input type="text" name="fullname" required>
        <span>Full Name</span>
        <i></i>
      </div>
      <div class="inputbox">
        <input type="text" name="username" required>
        <span>Username</span>
        <i></i>
      </div>
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
        <a href="#">Already have an account?</a>
        <a href="login.php">Login</a>
      </div>
      <input type="submit" value="Sign Up">
      <!-- Admin Login Button -->
      <a href="adminlogin.php" class="admin-btn">Admin Login</a>
    </form>
  </div>
</body>
</html>