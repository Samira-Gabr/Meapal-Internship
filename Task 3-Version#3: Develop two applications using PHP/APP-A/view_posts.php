<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit ();
}
require 'functions.php';

$xmlData = new SimpleXMLElement('<request/>');
$xmlData->addChild('action', 'fetch_approved_posts');

$headers = [
    'Content-Type: application/xml',
    'Authorization: Token 2#7#7#2#0#0#0'
];
$response = SendCurl('http://backend.local/api/view_posts.php', $xmlData->asXML(), $headers);

$posts = [];

if ($response) {
    $responsexml = simplexml_load_string($response);
    if ($responsexml->status == 'success' && isset($responsexml->post)) {
        foreach ($responsexml->post as $post) {
            $posts[] = [
                'title'    => (string) $post->title,
                'content'  => (string) $post->content,
                'username' => (string) $post->username,
                'image'    => isset($post->image) ? (string) $post->image : null
            ];
        }
    } else {
        echo "Error =(" . htmlspecialchars($responsexml->message);
    }
} else {
    echo "Error fetching posts =o";
}
$user_role = $_SESSION['role'];
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
        <a href="<?php echo ($user_role === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>">
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