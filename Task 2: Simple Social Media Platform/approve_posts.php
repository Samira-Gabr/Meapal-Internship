<?php
session_start();
require 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['post_id'];
    if (isset($_POST['approve'])) {
        $SQL_Q = "UPDATE posts SET approved = 1 WHERE id = :post_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['post_id' => $postId]);
        
    } elseif (isset($_POST['disapprove'])) {
        $SQL_Q = "UPDATE posts SET approved = 0 WHERE id = :post_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['post_id' => $postId]);

    } elseif (isset($_POST['delete'])) {
        $SQL_Q = "DELETE FROM posts WHERE id = :post_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['post_id' => $postId]);
    }
}
$SQL_Q = "SELECT posts.*, users.username FROM posts INNER JOIN users ON posts.user_id = users.id";
$stmt = $pdo->query($SQL_Q);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Posts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Manage User Posts</h1>
    <table>
        <thead>
            <tr>
                <th>Post ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Author</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo htmlspecialchars($post['id']); ?></td>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($post['content'])); ?></td>
                    <td><?php echo htmlspecialchars($post['username']); ?></td>
                    <td><?php echo $post['approved'] ? 'Approved' : 'Pending Approval'; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                            <input type="submit" name="approve" value="Approve">
                            <input type="submit" name="disapprove" value="Disapprove">
                            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this post?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
