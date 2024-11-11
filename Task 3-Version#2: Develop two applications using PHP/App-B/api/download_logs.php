<?php
$logfilepath = 'log.txt';

header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');
$header = getallheaders();
if (!isset($header['Authorization']) || $header['Authorization'] !== API_TOKEN) {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid API token :(');
    echo $response->asXML();
    exit();
}

if (!file_exists($logfilepath)) {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Log file does not exist.');
    echo $response->asXML();
    exit();
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="logs.txt"');
header('Content-Length: ' . filesize($logfilepath));

readfile($logfilepath);
exit();
?>
