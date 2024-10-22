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
        $_SESSION['profile_pic'] = $user['profile_pic'];

        header("Location: MyProfile.php");
        exit;
    } else {
        echo "Invalid Login credentials!!!";
    }
}
?>


<form action="login.php" method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
</form>