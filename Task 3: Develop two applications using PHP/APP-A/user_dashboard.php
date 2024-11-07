<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$log_file = 'log.txt';

$xmlData = new SimpleXMLElement('<request/>');
$xmlData->addChild('action', 'get_user_info');
$xmlData->addChild('user_id', $userID);
/*--------------------------------------*/
$ch = curl_init('http://backend.local/api/user_dashboard.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData->asXML());
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'));
$response = curl_exec($ch);
curl_close($ch);
/*----------------------------------------*/
if ($response) {
    $responsexml = simplexml_load_string($response);
    echo "<pre>";
    var_dump($responsexml);
    echo "</pre>";
    if ((string)$responsexml->status === 'success') {
        $username = (string)$responsexml->username;
        $email = (string)$responsexml->email;
        $profile_pic = (string)$responsexml->profile_pic;
        $role = (string)$responsexml->role;
    } else {
        echo "I can't retrieve user information: " . (string)$responsexml->message;
        exit();
    }
} else {
    echo "Can't communicate with the server.";
    exit();
}
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
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <p>Role: <?php echo htmlspecialchars($role); ?></p>
    <?php if ($profile_pic): ?>
        <img class="profile-pic" src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
    <?php endif; ?>
    <div class="links">
        <a href="create_post.php">Create Post</a> |
        <a href="edit_profile.php">Edit Your Info</a> |
        <a href="view_posts.php">Show Other Posts</a> |
        <a href="admin_dashboard.php">Admin ?</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>
