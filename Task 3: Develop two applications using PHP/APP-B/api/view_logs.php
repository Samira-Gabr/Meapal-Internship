<?php
header('Content-Type: application/xml');
$logFilePath = 'log.txt';
$response = new SimpleXMLElement('<response/>');

if (file_exists($logFilePath)) {
    $logContents = file_get_contents($logFilePath);
    $response->addChild('status', 'success');
    $response->addChild('log_contents', htmlspecialchars($logContents));
} else {
    $response->addChild('status', 'error');
    $response->addChild('message', 'Log file does not exist.');
}

echo $response->asXML();
?>