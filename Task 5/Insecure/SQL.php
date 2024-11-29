<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "pen_test_lab"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT users.first_name, users.email, passwords.plain_password 
            FROM users 
            JOIN passwords ON users.id = passwords.user_id 
            WHERE users.email = '$email' AND passwords.plain_password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "First Name: " . $row['first_name'] . "<br>";
            echo "Email: " . $row['email'] . "<br>";
            echo "Password: " . $row['plain_password'] . "<br>";
        }
    } else {
        echo "Invalid email or password.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login Page</h2>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>