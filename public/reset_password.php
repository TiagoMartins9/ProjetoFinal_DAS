<?php
require_once '../App/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $db = new DB();
        $sql = 'SELECT * FROM user WHERE reset_token = ? AND reset_token_expiry > NOW()';
        $result = $db->send2db($sql, [$token]);

        if ($result->num_rows === 0) {
            echo 'Invalid or expired token';
        } else {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = 'UPDATE user SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?';
            $db->send2db($sql, [$hashedPassword, $token]);
            echo 'Password has been reset successfully';
        }
    } else {
        echo 'Passwords do not match';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
  <div class="login_form">
    <!-- Reset Password form container -->
    <form action="reset_password.php" method="POST">
      <h3>Reset Password</h3>

      <!-- Token input box (hidden) -->
      <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" />

      <!-- New Password input box -->
      <div class="input_box">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required />
      </div>

      <!-- Confirm Password input box -->
      <div class="input_box">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required />
      </div>

      <!-- Submit button -->
      <button type="submit">Reset Password</button>
    </form>
  </div>
</body>
</html>