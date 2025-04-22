<?php
session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$feedback = "";
$name = $_SESSION['fullname'] ?? '';
$email = $_SESSION['email'] ?? ''; // Assuming you store user email in session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yourgmail@gmail.com'; // your Gmail
            $mail->Password = 'your_app_password';   // app password from Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($email, $name);
            $mail->addAddress('phamgiakiet1911@gmail.com', 'Admin');

            $mail->Subject = $subject;
            $mail->Body = "From: $name <$email>\n\n$message";
            $mail->isHTML(false);

            $mail->send();
            $feedback = "✅ Message sent successfully!";
        } catch (Exception $e) {
            $feedback = "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $feedback = "⚠️ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Admin</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fff8f0;
      padding: 40px;
    }
    form {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    input[type="submit"] {
      background: #ff6600;
      color: #fff;
      border: none;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background: #e65c00;
    }
    .feedback {
      text-align: center;
      margin-bottom: 20px;
      color: #ff6600;
    }
  </style>
</head>
<body>

<form method="POST" action="">
  <h2>Contact Admin</h2>
  <?php if ($feedback): ?>
    <p class="feedback"><?= $feedback ?></p>
  <?php endif; ?>
  <label>Your Name:</label>
  <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

  <label>Your Email:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

  <label>Subject:</label>
  <input type="text" name="subject" required>

  <label>Message:</label>
  <textarea name="message" rows="6" required></textarea>

  <input type="submit" value="Send Message">
</form>

</body>
</html>


