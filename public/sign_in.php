<?php
require_once '../App/config.php';
require_once '../src/models/user.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $db = new DB();
        $result = NewUser($db, $username, $email, $password);

        if ($result === 'User created successfully') {
            $success_message = 'User created successfully';
        } else {
            $error_message = 'Error creating user';
        }
    } else {
        $error_message = 'Passwords do not match';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In - Gym Helper</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
  <div class="login_form">
    <!-- Sign In form container -->
    <form action="sign_in.php" method="POST">
      <h3>Sign Up</h3>

      <?php if ($success_message): ?>
        <div class="success_message"><?php echo htmlspecialchars($success_message); ?></div>
      <?php endif; ?>

      <?php if ($error_message): ?>
        <div class="error_message"><?php echo htmlspecialchars($error_message); ?></div>
      <?php endif; ?>

      <!-- Username input box -->
      <div class="input_box">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required />
      </div>

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

      <!-- Confirm Password input box -->
      <div class="input_box">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required />
      </div>

      <!-- Sign Up button -->
      <button type="submit">Sign Up</button>

      <p class="sign_up">Already have an account? <a href="login.php">Log in</a></p>
    </form>
  </div>
</body>
</html>