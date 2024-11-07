<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$xmlData = new SimpleXMLElement('<request/>');
$xmlData->addChild('action', 'fetch_user_info');
$xmlData->addChild('user_id', $userID);

$ch = curl_init('http://backend.local/api/info_admin.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData->asXML());
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'));
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $responsexml = simplexml_load_string($response);
    if ($responsexml->status == 'success') {
        $username    = (string)$responsexml->username;
        $email       = (string)$responsexml->email;
        $role        = (string)$responsexml->role;
        $profile_pic = (string)$responsexml->profile_pic;
    } else {
        echo "Error :)" . $responsexml->message;
        exit();
    } 
} else {
    echo "Error fetching user information.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Info</title>
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
        <a href="admin_dashboard.php">Go to Admin Dashboard</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>