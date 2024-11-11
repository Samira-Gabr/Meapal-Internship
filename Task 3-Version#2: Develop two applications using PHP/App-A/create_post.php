<?php
session_start();
$log_file = 'log.txt';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userID = $_SESSION['user_id'];
$message = "";

function PostXML($title, $content, $userID, $image = null) {
    $xmlData = new SimpleXMLElement('<request/>');
    $xmlData->addChild('action', 'create_post');
    $xmlData->addChild('title', $title);
    $xmlData->addChild('content', $content);
    $xmlData->addChild('user_id', $userID);
    if ($image) {
        $xmlData->addchild('image', $image);
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = null;
    /*----------------------------------------*/

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_extention = ['jpg', 'jpeg', 'png', 'gif'];
        $file_tmp_loc = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_extention)) {
            $new_name = uniqid() . '.' . $file_ext;
            if (!is_dir('uploads/posts')) {
                mkdir('uploads/posts', 440, true);
            }
            if (move_uploaded_file($file_tmp_loc, 'uploads/posts' . $new_name)) {
                $image = 'uploads/posts' . $new_name;
                $xmlData->addChild('image', $image);
            } else {
                $message = "Failed to upload the image";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }
    
$xmlData = PostXML($title, $content, $userID, $image);
$url ='http://backend.local/api/create_post.php';
$headers = ['Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0']; 
$response = SendCurl($url, $xmlData, $headers)

    if ($response) {
        $responsexml = simplexml_load_string($response);
        if ($responsexml->status == 'success') {
            $message = "Post submitted and awaiting admin approval! ;)";
        } else {
            $message = "Failed to submit your post :(";
        }
    } else {
        $message = "Error communicating with the server!!!!!";
    }
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