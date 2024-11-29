<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "pen_test_lab"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email    = $_POST['email'];
        $password = $_POST['passowrd'];
        $sql      = "SELECT users.first_name, users.email, passwords.plain_password
                     FROM users
                     JOIN passwords ON users.id = passwords.user_id
                     WHERE users.email = :email AND passwords.plain_password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email'  , $email, PDO::PARAM_STR);
        $stmt->bindParam('password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        IF (count($result) > 0) {
            foreach ($result as $row) {
                echo "First Name: " . htmlspecialchars($row['first_name']) . "<br>";
                echo "Email: " . htmlspecialchars($row['email']) . "<br>";
                echo "Password: " . htmlspecialchars($row['plain_password']) . "<br>";
            }
        } else {
            echo "invalid email or password";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
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