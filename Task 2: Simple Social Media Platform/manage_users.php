<?php
session_start();
require 'dbconn.php';
$SQL_Q = "SELECT * FROM users";
$stmt = $pdo->query($SQL_Q);
$users = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    if (isset($_POST['delete'])) {
        $SQL_Q = "DELETE FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['user_id' => $userId]);
    } elseif (isset($_POST['toggle_status'])) {
        $SQL_Q = "UPDATE users SET active = NOT active WHERE id = :user_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['user_id' => $userId]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Manage User Accounts</h1>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['active'] ? 'Active' : 'Deactivated'; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <a href="view_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">View</a>
                            <input type="submit" name="toggle_status" 
                                   value="<?php echo $user['active'] ? 'Deactivate' : 'Activate'; ?>" 
                                   onclick="return confirm('Are you sure you want to <?php echo $user['active'] ? 'deactivate' : 'activate'; ?> this user?');">
                            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>

