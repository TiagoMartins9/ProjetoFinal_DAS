<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../App/config.php';
require_once '../src/models/user.php';

$db = new DB();
$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = 'SELECT user_name, perfil_photo FROM user WHERE user_id = ?';
$result = $db->send2db($sql, [$user_id]);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['user_name'];
    $perfil_photo = base64_encode($user['perfil_photo']);
} else {
    echo 'User not found';
    exit();
}

// Update user name
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_name'])) {
    $new_name = $_POST['user_name'];
    $sql = 'UPDATE user SET user_name = ? WHERE user_id = ?';
    $db->send2db($sql, [$new_name, $user_id]);
    $user_name = $new_name; // Update the name in the current session
    echo "<script>alert('Nome de usuário atualizado com sucesso!');</script>";
}

// Update profile photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['perfil_image'])) {
    $updated_photo = updateProfilePhoto($user_id, $_FILES['perfil_image']);
    if ($updated_photo) {
        $perfil_photo = base64_encode($updated_photo); // Update the photo in the current session
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/perfil.css">
    <title>Profile - Gym Helper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
  <div class="wrapper">
    <div class="img-area">
      <div class="inner-area">
        <form id="perfilForm" action="" method="POST" enctype="multipart/form-data">
          <label for="perfil_image">
            <img src="data:image/jpeg;base64,<?php echo $perfil_photo; ?>" alt="perfil Photo" class="profile-photo">
          </label>
          <input type="file" name="perfil_image" id="perfil_image" style="display: none;" onchange="this.form.submit()">
        </form>
      </div>
    </div>
    <form id="nameForm" action="" method="POST">
      <div class="name">
        <input type="text" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" class="name-input">
      </div>
      <div class="button-container">
        <button type="submit" name="update_name" class="update-button">Update Name</button>
      </div>
    </form>
    <div class="about">Designer & Developer</div>
  </div>

  <script>
    document.getElementById('perfil_image').addEventListener('change', function() {
      document.getElementById('perfilForm').submit();
    });

    document.getElementById('nameForm').addEventListener('submit', function(event) {
      alert('Nome de usuário atualizado com sucesso!');
    });
  </script>
</body>
</html>