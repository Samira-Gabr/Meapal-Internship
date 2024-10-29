<?php
session_start();
require 'DataBaseConn.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $SQL_Q = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($SQL_Q);
    $stmt->execute(['email'=> $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: MyProfile.php");
        exit;
    } else {
        echo "Invalid Login credentials!!!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <form action="login.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>
    <div class="links">
        <a href="register.php">Register</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>