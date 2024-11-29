<?php
$users = [
    'admin' => 'admin',
    'user' => 'user'  
];

function render_template($template, $data) {
    foreach ($data as $key => $value) {
        $template = str_replace("{{" . $key . "}}", htmlspecialchars($value), $template);
    }
    return $template;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $welcomeMessage = $_POST['template'] ?? 'Welcome, {{username}}!';
        echo render_template($welcomeMessage, ['username' => $username]);
    } else {
        echo "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="template">Template:</label>
        <input type="text" name="template" id="template" placeholder="Welcome, {{username}}!">
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>