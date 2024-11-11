<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="dashboard-icons">
        <a href="view_logs.php" class="icon">
            <img src="icon/logs.png" alt="View Logs">
            <span>View Logs</span>
        </a>
        <a href="manage_users.php" class="icon">
            <img src="icon/users.png" alt="Manage Users">
            <span>Manage Users</span>
        </a>
        <a href="approve_posts.php" class="icon">
            <img src="icon/approve_posts.png" alt="Approve Posts"> 
            <span>Approve Posts</span>
        </a>
        <a href="view_posts.php" class="icon">
            <img src="icon/view_posts.png" alt="View Posts"> 
            <span>View Posts</span>
        </a>
        <a href="info_admin.php" class="icon">
            <img src="icon/info.png" alt="Information"> 
            <span>Information</span>
        </a>
        <a href="create_post.php" class="icon">
            <img src="icon/create.png" alt="Create Posts"> 
            <span>Create Posts</span>
        </a>
        <a href="logout.php" class="icon">
            <img src="icon/logout.png" alt="Logout">
            <span>Logout</span>
        </a>
    </div>
</div>
</body>
</html>
