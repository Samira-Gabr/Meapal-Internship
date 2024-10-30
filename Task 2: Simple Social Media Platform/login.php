<?php
session_start();
require 'dbconn.php';
$log_file = 'log.txt';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $login = $_POST['login'];
    $password =$_POST['password'];

    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $SQL_Q = "SELECT * FROM users WHERE email = :login";

    } else {
        $SQL_Q = "SELECT * FROM users WHERE username = :login";
    }

    $stmt = $pdo->prepare($SQL_Q);
    $stmt->execute(['login' => $login]) ;
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        file_put_contents($log_file, "User ID {$user['id']} logged in at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        
        if ($user['role'] == 'Admin') {
            setcookie('role', $user['role'], time() + (86400 * 7), "/");
            header("Location: admin_dashboard.php");
        } else {
            setcookie('role', $user['role'], time() + (86400 * 7), "/");
            header("Location: user_dashboard.php");
        }
         
        exit(); 
    } else {
        echo "Invalid login credentials!";
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
        <label>Username or Email:</label>
        <input type="text" name="login" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <input type="submit" value="Login">
    </form>
    <div class="links">
        <a href="register.php">Register</a>
    </div>
</div>
</body>
</html>