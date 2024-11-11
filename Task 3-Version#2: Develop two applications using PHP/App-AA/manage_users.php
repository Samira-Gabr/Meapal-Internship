<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header ("Location: login.php");
    exit ();
}
$log_file = 'log.txt';

function GetData($action, $data = []) {
    $xmlData = New SimpleXMLElement('<request/>');
    $xmlData->addChild('action', $action);
    foreach ($data as $key => $value ) {
        $xmlData->addChild($key, $value);
    }
    return $xmlData->asXML();
}
function SendCurl($url, $xmlData, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
$url       = 'http://backend.local/api/manage_users.php';
$headers = ['Content-Type: application/xml', 'Authorization: Token 2#7#7#2#0#0#0'];
$actiontype = GetData('fetch_all_users');
$response   = SendCurl($url, $actiontype, $headers);

if ($response) {
    $responsexml = simplexml_load_string($response);
    $users = $responsexml->user ?? [];
    if (!$users) {
        echo "No Users found";
    }
} else {
    echo "I can't communicat with the serve :(";
    exit ();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['user_id'];
    $actiontype     = isset($_POST['delete']) ? 'delete' : 'toggle_status';
    $actionAdmin    = GetData($actiontype, ['user_id' => $userID]);
    $actionresponse = SendCurl($url, $actionAdmin, $headers);

    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] User {$_SESSION['user_id']} performed action: $actiontype ";
    file_put_contents($log_file, $log_message . "\n", FILE_APPEND);

    if ($actionresponse) {
        $response = simplexml_load_string($actionresponse);
        if ($response->status == 'success'){
            header("Location: manage_users.php");
            exit ();
        }
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
<div class="links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
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
</div>
</body>
</html>