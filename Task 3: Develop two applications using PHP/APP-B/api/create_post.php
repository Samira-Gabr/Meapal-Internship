<?php
require 'dbconn.php';
header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $xmlInput   = file_get_contents('php://input');
    $requestxml = simplexml_load_string($xmlInput);
    $action     = (string)$requestxml->action;
    $userID     = (string)$requestxml->user_id;
    $title      = (string)$requestxml->title;
    $content    = (string)$requestxml->content;
    $image      = (string)$requestxml->image ?? null;


    $SQL_Q = "INSERT INTO posts (user_id, title, content, image, approved) VALUES (:user_id,:title, :content, :image, 0)";
    $stmt = $pdo->prepare($SQL_Q);
    $success = $stmt->execute([
        'user_id' => $userID,
        'title' => $title,
        'content' => $content,
        'image' => $image
    ]);
    $response = new SimpleXMLElement('<response/>');
    if ($success) {
        $response->addChild('status', 'success');
    } else {
        $response->addChild('status', 'error');
    }
    echo $response->asXML();

} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}
?>