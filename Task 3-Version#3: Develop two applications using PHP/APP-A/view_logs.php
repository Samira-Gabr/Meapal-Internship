<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit ();
}
require 'functions.php';

$xmlData = new SimpleXMLElement('<request/>');
$xmlData->addChild('action', 'fetch_logs');

$headers = [
    'Content-Type: application/xml',
    'Authorization: Token 2#7#7#2#0#0#0'
];
$response = SendCurl('http://backend.local/api/view_logs.php', $xmlData->asXML(), $headers);

$logContents = "";
$logFileExists = false;

if ($response) {
    $responsexml = simplexml_load_string($response);
    if ($responsexml->status == 'success') {
        $logContents = nl2br(htmlspecialchars($responsexml->log_contents));
        $logFileExists = true;
    } else {
        echo "Error: " . htmlspecialchars((string)$responsexml->message);
    }
} else {
    echo "Error fetching log file" ;
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
        <br>
        <a href="download_log.php" class="download-button">Download Log File</a>
    <?php else: ?>
        <p>Log file does not exist or could not be retrieved.</p>
    <?php endif; ?>
    <div class="links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
