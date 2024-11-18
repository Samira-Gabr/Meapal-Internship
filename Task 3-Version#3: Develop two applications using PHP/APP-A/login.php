<?php
session_start();
$log_file = 'log.txt';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $login = $_POST['login'];
    $password  = $_POST['password'];
    $xmlData = new SimpleXMLElement('<request/>');
    $xmlData-> addChild('action', 'login');
    $xmlData-> addChild('login', htmlspecialchars($login));
    $xmlData-> addChild('password', htmlspecialchars($password));
    /*--------------*/
    $headers = [
        'Content-Type: application/xml',
        'Authorization: Token 2#7#7#2#0#0#0'
    ];
    $response = SendCurl('http://backend.local/api/login.php', $xmlData->asXML(), $headers);
    /*-----------------*/
    if ($response) {
        $responsexml = simplexml_load_string($response);

        if ((string)$responsexml->status === 'success') {
            $_SESSION['user_id'] = (string)$responsexml->user_id;
            $_SESSION['username'] = (string)$responsexml->username;
            $_SESSION['role'] = (string)$responsexml->role;
            file_put_contents($log_file, "User ID {$user_id} logged in at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            /*----------------------*/
            if ($_SESSION['role'] == 'Admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_dashboard.php');
            }
            exit();
        } else {
            file_put_contents($log_file, "Failed login attempt for username/email '{$username_or_email}' at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            echo "Invalid credentials :-|" . (string)$responsexml->message;
        }
    } else {
        echo "I Can't communicat with the server :-(";
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