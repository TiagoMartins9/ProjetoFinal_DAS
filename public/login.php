<?php
require_once '../App/config.php';

session_start();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new DB();
    $sql = 'SELECT * FROM user WHERE user_email = ?';
    $result = $db->send2db($sql, [$email]);

    if ($result->num_rows === 0) {
        $error_message = 'Email not found';
    } else {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['user_pass'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['user_name'];
            $_SESSION['email'] = $user['user_email'];
            $_SESSION['password'] = $user['user_pass'];

            // Redirect to dashboard
            header('Location: perfil.php');
            exit();
        } else {
            $error_message = 'Incorrect password';
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Gym Helper</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
  <div class="login_form">
    <!-- Login form container -->
    <form action="login.php" method="POST">
      <h3>Log in</h3>

      <?php if ($error_message): ?>
        <div class="error_message"><?php echo htmlspecialchars($error_message); ?></div>
      <?php endif; ?>

      <!-- Email input box -->
      <div class="input_box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter email address" required />
      </div>

      <!-- Password input box -->
      <div class="input_box">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />
      </div>

      <!-- Log in button -->
      <button type="submit">Log in</button>

      <p class="sign_up">Don't have an account? <a href="sign_in.php">Sign up</a></p>
      <p class="forgot_password"><a href="forgotpass.php">Forgot your password?</a></p>
    </form>
  </div>
</body>
</html>