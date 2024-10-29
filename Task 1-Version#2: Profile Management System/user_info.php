<?php
session_start();
require 'DataBaseConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$SQL_Q = "SELECT * FROM users WHERE id= :user_id";
$stmt = $pdo->prepare($SQL_Q);
$stmt-> execute(['user_id' => $user_id]);
$user = $stmt->fetch();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>User Information</h1>

    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Hashed Password: <?php echo htmlspecialchars($user['password']); ?></p>
    <?php if ($user['profile_pic']): ?>
        <p>Profile Picture:</p>
        <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" width="150">
    <?php else: ?>
        <p>No profile picture uploaded.</p>
    <?php endif; ?>

    <div class="links">
        <a href="register.php">Register New Account</a> |
        <a href="login.php">Login with Another Account</a> |
        <a href="myprofile.php">My Profile</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>