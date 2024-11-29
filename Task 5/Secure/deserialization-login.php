<?php
session_start();
$users = ['admin' => ['password' => password_hash('admin123', PASSWORD_DEFAULT), 'role'=> 'admin'],
          'user'  => ['password' =>password_hash('user123', PASSWORD_DEFAULT), 'role' => 'user']];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username       = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password       = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $SerializedData = filter_input(INPUT_POST, 'SerializedData ', FILTER_SANITIZE_STRING);
    $UserData = null;
    if ($SerializedData) {
        $UserData = json_decode($SerializedData, TRUE);
    }
    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])){
        $_SESSION['username'] = $username;
        $_SESSION['role']     = $UserData['role'] ?? $users[$username]['role'];
        echo "Login successful! welcome, " . htmlspecialchars($_SESSION['role']);
        exit;
    } else {
        echo "invalid credentials!";
    }
}
////O:8:"stdClass":1:{s:4:"role";s:5:"admin";}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="user_data">User Data (JSON format):</label>
        <input type="text" name="user_data" id="user_data"><br><br>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>