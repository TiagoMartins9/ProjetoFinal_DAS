<?php
require_once '../App/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $db = new DB();
    $sql = 'SELECT * FROM user WHERE user_email = ?';
    $result = $db->send2db($sql, [$email]);

    if ($result->num_rows === 0) {
        echo 'User not found';
    } else {
        $token = bin2hex(random_bytes(50));
        $sql = 'UPDATE user SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE user_email = ?';
        $db->send2db($sql, [$token, $email]);

        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: your-email@gmail.com\r\n";
        $headers .= "Reply-To: your-email@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo 'Password reset link has been sent to your email';
        } else {
            echo 'Failed to send email';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - Gym Helper</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
  <div class="login_form">
    <!-- Forgot Password form container -->
    <form action="forgotpass.php" method="POST">
      <h3>Forgot Password</h3>

      <!-- Email input box -->
      <div class="input_box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter email address" required />
      </div>

      <!-- Submit button -->
      <button type="submit">Submit</button>

      <p class="sign_up">Remembered your password? <a href="login.php">Log in</a></p>
    </form>
  </div>
</body>
</html>