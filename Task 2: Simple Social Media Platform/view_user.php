<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
/* || $_SESSION['role'] !== 'admin'*/

$userId = $_GET['id'];
$SQL_Q = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($SQL_Q);
$stmt->execute(['user_id' => $userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "User Not Found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>User Information</h1>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Status:</strong> <?php echo $user['active'] ? 'Active' : 'Deactivated'; ?></p>
    <div class="links">
        <a href="manage_users.php">Back to Manage Users</a>
    </div>
</div>
</body>
</html>