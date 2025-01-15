<?php

function NewUser($db, $username, $email, $password) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert a new user
    $sql = 'INSERT INTO user (user_name, user_email, user_pass) VALUES (?, ?, ?)';

    // Prepare the statement
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bind_param('sss', $username, $email, $hashedPassword);

    // Execute the query
    if ($stmt->execute()) {
        return 'User created successfully';
    } else {
        return 'Error creating user: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}


function updateProfilePhoto($user_id, $file) {
    $imageData = file_get_contents($file["tmp_name"]);
    $db = new DB();
    $sql = 'UPDATE user SET perfil_photo = ? WHERE user_id = ?';
    $db->send2db($sql, [$imageData, $user_id]);
    return $imageData;
}
?>