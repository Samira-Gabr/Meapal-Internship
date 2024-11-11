<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header ("Location: login.php");
    exit ();
}
$log_file = 'log.txt';

function ActionXml($action, $posts = []) {
    $xmlData = new SimpleXMLElement('<request/>');
    $xmlData->addchild('action', $action);
    foreach ($posts as $key => $value) {
        $xmlData->addchild($key, $value);
    }
    return $xmlData->asXML();
}

function SendCurl($url, $xmlData, $headers = []){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
return $response;
}

$url = 'http://backend.local/api/approve_posts.php';
$headers = ['Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'];

$fetchPosts = ActionXml('fetch_all_posts');
$response   = SendCurl($url, $fetchPosts, $headers);

/*------------------------------------*/
if ($response) {
    $responsexml = simplexml_load_string($response);
    $posts = $responsexml->post ?? [];
} else {
    echo "we can't communicat with the serve :(";
    exit();
}
/*------------------------------------*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postId = $_POST['post_id'];
    $actionType = $_POST['action'];

$action = ActionXml($actionType, ['post_id' => $postId]);
$actionResponse = SendCurl($url, $action, $headers);

    if ($actionResponse) {
        header("Location: approve_posts.php");
        exit();
    } else {
        echo "Error processing the action.";
        exit();
    }
}
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
            <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post->id); ?></td>
                        <td><?php echo htmlspecialchars($post->title); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($post->content)); ?></td>
                        <td><?php echo htmlspecialchars($post->username); ?></td>
                        <td><?php echo $post->approved == '1' ? 'Approved' : 'Pending Approval'; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post->id); ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                                <button type="submit" name="action" value="disapprove">Disapprove</button>
                                <button type="submit" name="action" value="delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No posts available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>