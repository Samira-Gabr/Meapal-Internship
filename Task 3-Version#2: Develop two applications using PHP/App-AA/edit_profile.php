<?php
session_start();
$log_file = 'log.txt'; 
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit();
}
$userID = $_SESSION['user_id'];

function ActionXml ($action, $data = []) {
    $xmlData = new SimpleXMLElement('<request/>');
    $xmlData->addChild('action', $action);
    foreach ($data as $key => $value) {
    $xmlData->addChild($key, $value);
    }
    return $xmlData->asXML();
}

function SendCurl($url, $xmlData, $headrs = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headrs);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$url     = 'http://backend.local/api/edit_profile.php';
$headers = ['Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'];

$fetchuser = ActionXML('fetch_all_user', ['user_id' => $userID]);
$response  = SendCurl($url, $fetchuser, $headers);

if ($response) {
    $responsexml = simplexml_load_string($response);
    $user = $responsexml->user ?? null;
    if (!$user) {
        echo "user not found" ;
    }
} else {
    echo "I can't communicate with the server :< !!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : (string)$user->password;
    $profile_pic = (string)$user->profile_pic;

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_tmp_loc = $_FILES['profile_pic']['tmp_name']; 
        $file_name = uniqid() . '.' . pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        if (!is_dir('uploads')) {
            mkdir('uploads', 440, true);
        }
        if (move_uploaded_file($file_tmp_loc, 'uploads/' . $file_name)) {
            $profile_pic = $file_name;
        } else {
            echo "Failed to upload the profile picture :O";
        }
    }
$updateuser = ActionXml('update_user', [
    'user_id'     => $userID,
    'username'    => $username,
    'email'       => $email,
    'password'    => $password,
    'profile_pic' => $profile_pic ]);

$responseUpdate = SendCurl($url, $updateuser, $headers);

$timestamp = date('Y-m-d H:i:s');
$log_message = "[$timestamp] User {$_SESSION['user_id']} performed action: Update his profile info.";
file_put_contents($log_file, $log_message . "\n", FILE_APPEND);

    if ($responseUpdate) {
        $responseUpdateXml = simplexml_load_string($responseUpdate);
        if ($responseUpdateXml->status == 'success') {
            header("Location: user_dashboard.php"); 
            exit();
        } else {
            echo "Profile update failed: " . $responseUpdateXml->message;
        }
    } else {
        echo "Error: we can't update your data";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Profile</h1>
    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user->username); ?>" required>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
        <label>New Password (leave blank to keep current password):</label>
        <input type="password" name="password">
        <label>Profile Picture:</label>
        <?php if ($user->profile_pic): ?>
            <img src="uploads/<?php echo htmlspecialchars($user->profile_pic); ?>" alt="Profile Picture" width="100">
        <?php endif; ?>
        <input type="file" name="profile_pic">
        <input type="submit" value="Update Profile">
    </form>
    <form action="user_dashboard.php" method="get">
        <button type="submit">Back to Dashboard</button>
    </form>
</div>
</body>
</html>