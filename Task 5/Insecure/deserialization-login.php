<?php
$users = ['admin' => ['password' => 'admin123', 'role' => 'admin'],
          'user'  => ['password' => 'user123',  'role' => 'user']];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
}

$SerializedData = $_POST['user_data'] ?? '';
if ($SerializedData) {
    $UserData = (array)unserialize($SerializedData);

    if (isset($users[$username]) && $users[$username]['password'] ===$password) {
        $_SESSION['username'] = $username;
        $_SESSION['role']     = $UserData['role'];

        echo "Login successful! Welcome, " . htmlspecialchars($_SESSION['role']);
        exit;
    } else {
        echo "Invalid credentials!!";
    }
}

//O:8:"stdClass":1:{s:4:"role";s:5:"admin";}

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
        <label for="user_data">User Data (Serialized):</label>
        <input type="text" name="user_data" id="user_data"><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>