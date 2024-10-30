<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

/*|| $_SESSION['role'] !== 'admin'*/

$user_role = $_SESSION['role'];
$SQL_Q = "SELECT posts.* , users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.approved = 1";
$stmt = $pdo->query($SQL_Q);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Other Users' Posts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Posts from Other Users</h1>
        <div class="dashboard-link">
        <a href="<?php echo ($user_role === 'admin') ? 'admin_dashboard.php' : 'info_admin.php'; ?>">
            Go to Your Dashboard
        </a>
    </div>
    <?php if ($posts): ?>
        <div class="posts">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p>by <?php echo htmlspecialchars($post['username']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <?php if ($post['image']): ?>
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" width="300">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No posts available at the moment.</p>
    <?php endif; ?>
</div>
</body>
</html>