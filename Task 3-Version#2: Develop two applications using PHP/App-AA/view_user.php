<?php
session_start();

if (!isset($_GET['id'])) {
    echo "No user ID specified.";
    exit();
}
$userId = $_GET['id'];

$xmlRequest = new SimpleXMLElement('<request/>');
$xmlRequest->addChild('action', 'fetch_user');
$xmlRequest->addChild('user_id', htmlspecialchars($userId));

$ch = curl_init('http://backend.local/api/view_user.php'); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest->asXML());
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml',  'Authorization: Token 2#7#7#2#0#0#0'));
$response = curl_exec($ch);
curl_close($ch);

$user = null;

if ($response) {
    $responseXml = simplexml_load_string($response);
    if ($responseXml->status == 'success') {
        $user = [
            'username' => (string) $responseXml->user->username,
            'email'    => (string) $responseXml->user->email,
            'role'    => (string) $responseXml->user->role,
            'active'   => (string) $responseXml->user->active == '1' ? 'Active' : 'Deactivated'
        ];
    } else {
        echo "Error: " . htmlspecialchars($responseXml->message);
        exit();
    }
} else {
    echo "Error fetching user details.";
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
    <?php if ($user): ?>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($user['active']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
    <div class="links">
        <a href="manage_users.php">Back to Manage Users</a>
    </div>
</div>
</body>
</html>
