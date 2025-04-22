<?php
require 'db_connection.php'; // Database connection file
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Save the token in the database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);

        // Send reset email
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        
        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.your-email-provider.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com';
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourwebsite.com', 'Your Website');
            $mail->addAddress($email);

            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the following link to reset your password: $resetLink";

            $mail->send();
            echo 'Password reset link sent to your email.';
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Email not found.';
    }
}
?>

<form method="POST" action="">
    <label for="email">Enter your email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send Reset Link</button>
</form>
