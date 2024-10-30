<?php
require 'dbconn.php' ;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $profile_pic = null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error']== 0){
        $file_tmp_loc = $_FILES['profile_pic']['tmp_name'];
        $file_name = uniqid() . '.' . pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

        if (!is_dir('uploads')) {
            mkdir('uploads', 777, True);
        }
        if(move_uploaded_file($file_tmp_loc,'uploads/' . $file_name)){
            $profile_pic = $file_name;
        } else {
            echo "Failed to upload the Profile Picture";
        }
    }
    $SQL_Q = "INSERT INTO users (username, email, password, profile_pic, role) VALUES (:username, :email, :password, :profile_pic, :role)";
    $Stmt = $pdo->prepare($SQL_Q);
    $Stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'profile_pic' => $profile_pic,
        'role' => $role
    ]);
    if ($Stmt) {
        
        if ($role == 'Admin') {
            setcookie('role', $role, time() + (86400 * 7), "/");
            header("Location: login.php");
            exit();
        }else {
            setcookie('role', $role, time() + (86400 * 7), "/");
            header("Location: login.php");
        }
    } else {
        echo "Registration failed.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Register</h1>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <label>Profile Picture:</label>
        <input type="file" name="profile_pic">
        <label>Role:</label>
        <select name="role" required>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>
        <input type="submit" value="Register">
    </form>
    <div class="links">
        <a href="login.php">Login</a> 
    </div>
</div>
</body>
</html>
