<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $XmlData = file_get_contents('php://input');
    libxml_disable_entity_loader(false);
    $xml = simplexml_load_string($XmlData);
    $username = (string)$xml->username;
    $password = (string)$xml->password;

    if ($username == "admin" && $password == "admin") {
        echo "Login Successful!!";
    } else {
        echo "invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<body>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>