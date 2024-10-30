<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $profile_pic = $user['profile_pic'];
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $password = $user['password'];
    }
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_tmp_loc = $_FILES['profile_pic']['tmp_name'];
        $file_name = uniqid() . '.' . pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

        if (!is_dir('uploads')) {
            mkdir('uploads', 777, true);
        }

        if (move_uploaded_file($file_tmp_loc, 'uploads/' . $file_name)) {
            $profile_pic = $file_name;
        } else {
            echo "Failed to upload the profile picture.";
        }
    }
    $SQL_Q = "UPDATE users SET username = :username, email = :email, password = :password, profile_pic = :profile_pic WHERE id = :id";
    $stmt = $pdo->prepare($SQL_Q);
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'profile_pic' => $profile_pic,
        'id' => $userId
    ]);
    echo "Profile updated successfully!";
    header("Location: user_dashboard.php");
    exit();
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
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <label>New Password (leave blank to keep current password):</label>
        <input type="password" name="password">
        <label>Profile Picture:</label>
        <?php if ($user['profile_pic']): ?>
            <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" width="100">
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
