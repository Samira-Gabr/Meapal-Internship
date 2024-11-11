<?php
$log_file = 'log.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_pic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_tmp_loc = $_FILES['profile_pic']['tmp_name'];
        $file_name = $_FILES['profile_pic']['name'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            if (!is_dir('uploads')) {
                mkdir('uploads', 777, true);
            }
            if (move_uploaded_file($file_tmp_loc, 'uploads/' . $new_file_name)) {
                $profile_pic = $new_file_name;
            } else {
                echo "Failed to upload the Profile Picture" ;
                exit();
            }
        }
    }/*----------------------------------------------*/
    $xmlData = new SimpleXMLElement('<request/>');
    $xmlData->addChild('action', 'register');
    $xmlData->addChild('username', $username);
    $xmlData->addChild('email', $email);
    $xmlData->addChild('password', $password);
    $xmlData->addChild('profile_pic', $profile_pic);
    $xmlData->addChild('role', $role);
    /*-----------------------------------------------*/
    $ch = curl_init('http://backend.local/api/register.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData->asXML());
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'));
    $response = curl_exec($ch);
    curl_close($ch);
    /*-------------------------------------------------*/
    if ($response) {
        $responsexml = simplexml_load_string($response);
        echo "<pre>";
        var_dump($responsexml);
        echo "</pre>";
        if ((string) $responsexml->status === 'success') {
            file_put_contents($log_file, "User '{$username}' registered successfully at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            setcookie('role', $role, time() + (86400 * 7), "/");
            if ($role == 'Admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_dashboard.php');
            }
            exit(); 
        } else {
            file_put_contents($log_file, "Failed registration attempt for '{$username}' at " . date('Y-m-d H:i:s') . " - Reason: " . (string)$responsexml->message . "\n", FILE_APPEND);
            echo "Registration failed: " . (string)$responsexml->message;
        }
    } else {
        echo "I Can't communicat with the server :-(";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Register</h1>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <label>Profile Picture:</label>
        <input type="file" name="profile_pic">
        <label>Role:</label>
        <select name="role" required>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>
        <input type="submit" value="Register">
    </form>
    <div class="links">
        <a href="login.php">Login</a> 
    </div>
</div>
</body>
</html>