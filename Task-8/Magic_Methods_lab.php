<?php
class User {
    public   $username;
    public   $password;
    private  $isLoggedIn = false;
    //user data لل  Initializes عشان نعملوا  __construct method هنا هنستخدموا ال  
   
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        echo "User object created for $this->username\n";
    }
    //user data لل  destroy عشان نعملوا  __destruct() method هنا هنستخدموا ال  

    public function __destruct() {
        echo "User object for $this->username is destroyed\n";
    }
    // __toString: string ل object بنحولوا بيها ال  
    public function __toString() {
        return $this->isLoggedIn ? "Welcome, $this->username!" : "Please log in.";
    }

    //Called before serializing the object
    // isLoggedIn , username  لل serialize هنعملوا 
     public function __sleep() {
        echo "Serializing User object...\n";
        return ['username', 'isLoggedIn']; 
    }
    //Called after deserializing the object
    public function __wakeup() {
        echo "User object has been deserialized.\n";
    }
    
    public function login($username, $password) {
        if ($this->username === $username && $this->password === $password) {
            $this->isLoggedIn = true;
        } else {
            $this->isLoggedIn = false;
        }
    }
    public function saveUserData() {
        return serialize($this);
    }

    public function loadUserData($serializedData) {
        return unserialize($serializedData);
    }

    public function __get($name) {
        if ($name === 'isLoggedIn') {
            return $this->isLoggedIn;
        }
    }
    public function __set($name, $value)
    {
        if ($name === 'isLoggedIn') {
            $this->isLoggedIn = $value;
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $storedUsername = "admin";
    $storedPassword = "password123";
    $user = new User($storedUsername, $storedPassword);
    $user->login($_POST['username'], $_POST['password']);
    $serializedUser = $user->saveUserData();
    $user = $user->loadUserData($serializedUser);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Login Page with Magic Methods</title>
</head>
<body>
    <h2>Login Page</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <?php
    if (isset($user)) {
        echo "<p>" . $user . "</p>";
    }
    ?>

</body>
</html>
