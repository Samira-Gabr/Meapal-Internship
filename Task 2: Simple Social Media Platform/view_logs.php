<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* || $_SESSION['role'] !== 'admin'*/

$logFilePath = 'log.txt';
$logFileExists = file_exists($logFilePath);
$logContents = "";
if ($logFileExists) {
    $logContents = nl2br(htmlspecialchars(file_get_contents($logFilePath)));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Log File Viewer</h1>
    <?php if ($logFileExists): ?>
        <h2>Contents of logs.txt:</h2>
        <div class="log-contents">
            <?php echo $logContents; ?>
        </div>
        <a href="<?php echo $logFilePath; ?>" download>Download logs.txt</a>
    <?php else: ?>
        <p>Log file does not exist.</p>
    <?php endif; ?>
    <div class="links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>