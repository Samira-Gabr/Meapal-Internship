<?php
header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');

$logFilePath = 'log.txt';
$header = getallheaders();
if (!isset($header['Authorization']) || $header['Authorization'] !== API_TOKEN) {
  
  $response = new SimpleXMLElement('<response/>');
  $response->addChild('status', 'error');
  $response->addChild('message', 'Incorrect API token');
  echo $response->asXML();
  exit();
}
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