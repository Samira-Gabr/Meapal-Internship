<?php
session_start();
require 'DataBaseConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_name = $_FILES['profile_pic']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = uniqid() . '.' . $file_ext;

            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            if (move_uploaded_file($file_tmp, 'uploads/' . $new_file_name)) {
                $sql = "UPDATE users SET profile_pic = :profile_pic WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'profile_pic' => $new_file_name,
                    'user_id' => $user_id
                ]);
                $_SESSION['profile_pic'] = $new_file_name;
                $success_message = "Profile picture updated successfully!";
            } else {
                $error_message = "Failed to upload the profile picture.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }
}

$username = $user['username'];
$email = $user['email'];
$profile_pic = $user['profile_pic'];
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
<h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
<?php if ($profile_pic): ?>
    <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" width="150">
<?php else: ?>
    <p>No profile picture uploaded.</p>
<?php endif; ?>
<?php if (!empty($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
<?php if (!empty($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
<h2>Update Profile Picture</h2>
<form action="MyProfile.php" method="POST" enctype="multipart/form-data">
    <label>New Profile Picture:</label><br>
    <input type="file" name="profile_pic" required><br><br>
    <input type="submit" value="Update Picture">
</form>
<p>Email: <?php echo htmlspecialchars($email); ?></p>
<a href="Logout.php">Logout</a>

</body>
</html>
