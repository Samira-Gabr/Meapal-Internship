<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header ("Location: login.php");
    exit ();
}
$log_file = 'log.txt';
$xmlData = New SimpleXMLElement('<request/>');
$xmlData->addChild('action', 'fetch_all_users');

$ch = curl_init('http://backend.local/api/manage_users.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData->asXML());
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'));
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $responsexml = simplexml_load_string($response);
    $users = $responsexml->user;
} else {
    echo "I can't communicat with the serve :(";
    exit ();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['user_id'];
    $actiontype = isset($_POST['delete']) ? 'delete' : 'toggle_status';

    $xmlType = new SimpleXMLElement('<request/>');
    $xmlType->addChild('action', $actiontype);
    $xmlType->addChild('user_id', $userID);

    $ch = curl_init('http://backend.local/api/manage_users.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlType->asXML());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'));
    $actionresponse = curl_exec($ch);
    curl_close($ch);

    if ($actionresponse) {
        header("Location: manage_users.php");
        exit ();
    } else {
        echo "Error processing the action.";
        exit();
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
                    <td><?php echo htmlspecialchars($user->id); ?></td>
                    <td><?php echo htmlspecialchars($user->username); ?></td>
                    <td><?php echo htmlspecialchars($user->email); ?></td>
                    <td><?php echo $user->active == '1' ? 'Active' : 'Deactivated'; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user->id); ?>">
                            <a href="view_user.php?id=<?php echo htmlspecialchars($user->id); ?>">View</a>
                            <input type="submit" name="toggle_status" 
                                   value="<?php echo $user->active == '1' ? 'Deactivate' : 'Activate'; ?>" 
                                   onclick="return confirm('Are you sure you want to <?php echo $user->active == '1' ? 'deactivate' : 'activate'; ?> this user?');">
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