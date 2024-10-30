<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {

    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];
        $SQL_Q = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
        } else {
            header("Location: login.php");
            exit();
        }
    } else {
        header("Location: login.php");
        exit();
    }
}

$user_id = $_SESSION['user_id'];
$SQL_Q = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($SQL_Q);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not Found!";
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
    <title>User Dashboard</title>
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
    <p>Email: <?php echo htmlspecialchars($email);?></p>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <p>Role: <?php echo htmlspecialchars($role); ?></p> 
    <?php if ($profile_pic): ?>
        <img class="profile-pic" src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
    <?php endif; ?>
    <div class="links">
        <a href="create_post.php">Create Post</a> |
        <a href="edit_profile.php">Edit Your Info</a> |
        <a href="view_posts.php">Show Other Posts</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>