<?php
session_start();
require 'dbconn.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_tmp_loc = $_FILES['image']['tmp_name'];
        $file_name = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (!is_dir('uploads/posts')) {
            mkdir('uploads/posts', 777, true);
        }
        if (move_uploaded_file($file_tmp_loc, 'uploads/posts/' . $file_name)) {
            $image = 'uploads/posts/' . $file_name;
        } else {
            $message = "Failed to upload the image.";
        }
    }
    $SQL_Q = "INSERT INTO posts (user_id, title, content, image, approved) VALUES (:user_id, :title, :content, :image, 0)";
    $stmt = $pdo->prepare($SQL_Q);
    $stmt->execute([
        'user_id' => $userId,
        'title' => $title,
        'content' => $content,
        'image' => $image
    ]);
    $message = "Post submitted and awaiting admin approval!";

} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Create a New Post</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="create_post.php" method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Content:</label>
        <textarea name="content" rows="5" required></textarea>
        <label>Upload an Image (optional):</label>
        <input type="file" name="image">
        <input type="submit" value="Create Post">
    </form>
        <div class="links">
        <a href="user_dashboard.php">Go to User Dashboard</a>
    </div>
</div>
</body>
</html>