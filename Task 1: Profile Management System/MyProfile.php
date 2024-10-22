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
//--------------------//
    /*PROFILE PICTURE CHANGE */
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
                /* (Logging the change of the profile picture and add the message of these change to the file which we create called logs.txt) */
                $log_message = date('Y-m-d H:i:s') . " - User ID $user_id updated their profile picture to $new_file_name\n";
                file_put_contents('logs.txt', $log_message, FILE_APPEND);
            } else {
                $error_message = "Failed to upload the profile picture.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }
//--------------------//
    /* PASSWORD CHANGE */
    if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        /* We Check if the current password is correct or not by comparing the old one by the new one */
        if (password_verify($current_password, $user['password'])) {
            /* enter our new passwords two times to confirm */
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                /* Now we update our new password in the database */
                $sql = "UPDATE users SET password = :new_password WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'new_password' => $hashed_password,
                    'user_id' => $user_id
                ]);
                $success_message = "Password updated successfully!";
                /* (Logging the change of the password and add the message of these change to the file which we create called logs.txt) */
                $log_message = date('Y-m-d H:i:s') . " - User ID $user_id changed their password\n";
                file_put_contents('logs.txt', $log_message, FILE_APPEND);
            } else {
                $error_message = "New passwords do not match.";
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
//--------------------//
    /* EMAIL CHANGE */
    if (isset($_POST['new_email'])) {
        $new_email = $_POST['new_email'];
        /*the email is different from the current one ???*/
        if ($new_email !== $user['email']) {
            // Validate email format
            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                // CHANGE OUR  email in the database
                $sql = "UPDATE users SET email = :new_email WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'new_email' => $new_email,
                    'user_id' => $user_id
                ]);
                $success_message = "Email updated successfully!";
/* (Logging the change of the email and add the message of these change to the file which we create called logs.txt) */               
                $log_message = date('Y-m-d H:i:s') . " - User ID $user_id changed their email to $new_email\n";
                file_put_contents('logs.txt', $log_message, FILE_APPEND);
            } else {
                $error_message = "Invalid email format.";
            }
        } else {
            $error_message = "New email cannot be the same as the current one.";
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
<h2>Change Email</h2>
<form action="MyProfile.php" method="POST">
    <label>Current Email: <?php echo htmlspecialchars($email); ?></label><br><br>
    <label>New Email:</label><br>
    <input type="email" name="new_email" required><br><br>
    <input type="submit" value="Change Email">
</form>
<h2>Change Password</h2>
<form action="MyProfile.php" method="POST">
    <label>Current Password:</label><br>
    <input type="password" name="current_password" required><br><br>
    <label>New Password:</label><br>
    <input type="password" name="new_password" required><br><br>
    <label>Confirm New Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>
    <input type="submit" value="Change Password">
</form>
<p>Email: <?php echo htmlspecialchars($email); ?></p>
<a href="Logout.php">Logout</a>
</body>
</html>
