<?php
require 'DataBaseConn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $profile_pic = null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_tmp_loc = $_FILES['profile_pic']['tmp_name'];
        $file_name = uniqid() . '.' . pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($file_tmp_loc, 'uploads/' . $file_name)) {
            $profile_pic = $file_name;
        } else {
            echo "Failed to upload the profile picture";
        }
    }

    $SQL_Q = "INSERT INTO users (username, email, password, profile_pic) VALUES (:username, :email, :password, :profile_pic)";
    
    $Stmt = $pdo->prepare($SQL_Q);
    $Stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'profile_pic' => $profile_pic
    ]);

    echo "Registration successful!!";
}
?>

<form action="register.php" method="POST" enctype="multipart/form-data">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br>
    <label>Profile Picture:</label><br>
    <input type="file" name="profile_pic"><br><br>
    <input type="submit" value="Register">
</form>