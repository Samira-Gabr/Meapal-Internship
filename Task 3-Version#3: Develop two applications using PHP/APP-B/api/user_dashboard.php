<?php
require 'dbconn.php';
header('Content-Type: application/xml'); 
define('API_Token', 'Token 2#7#7#2#0#0#0');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $header = getallheaders();
    if (!isset($header['Authorization']) || $header['Authorization'] !== API_Token) {
        $response = new SimpleXMLElement('<response/>');
        $response->addChild('status', 'error');
        $response->addChild('message', 'Invalid API token :(');
        echo $response->asXML();
        exit();
    }

    $xmlInput = file_get_contents('php://input');
    $xml = simplexml_load_string($xmlInput);
    $userID = (string)$xml->user_id;
    /*-----------------------------------*/
    $SQL_Q = "SELECT * FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($SQL_Q);
    $stmt->execute(['user_id' => $userID]);
    $user = $stmt->fetch();
    /*-----------------------------------*/
    $response = new SimpleXMLElement('<response/>');
    if ($user) {
        $response->addChild('status', 'success');
        $response->addChild('username', $user['username']);
        $response->addChild('email', $user['email']);
        $response->addChild('profile_pic', $user['profile_pic']);
        $response->addChild('role', $user['role']);
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'User not found.');
    } 
    echo $response->asXML();

} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}
?>
