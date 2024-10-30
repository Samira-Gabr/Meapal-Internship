<?php
session_start();
require 'dbconn.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$SQL_Q = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($SQL_Q);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();
if (!$user) {
    echo "User not found!";
    exit();
}
$username = $user['username'];
$email = $user['email'];
$profile_pic = $user['profile_pic'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Info</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-pic {
            width: 100px;
            height: auto; 
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <p>Role: <?php echo htmlspecialchars($role); ?></p> 
    <?php if ($profile_pic): ?>
        <img class="profile-pic" src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
    <?php endif; ?>
    
    <div class="links">
        <a href="admin_dashboard.php">Go to Admin Dashboard</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>