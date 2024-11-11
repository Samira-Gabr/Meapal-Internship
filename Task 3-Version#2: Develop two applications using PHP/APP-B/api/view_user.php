<?php
require 'dbconn.php';
header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $header = getallheaders();
    if (!isset($header['Authorization']) || $header['Authorization'] !== API_TOKEN) {
        
        $response = new SimpleXMLElement('<response/>');
        $response->addChild('status', 'error');
        $response->addChild('message', 'Incorrect API token');
        echo $response->asXML();
        exit();
    }/*------------------------------------------*/

    $response = new SimpleXMLElement('<response/>');
    $requestXml = simplexml_load_string(file_get_contents('php://input'));
    if ($requestXml && $requestXml->action == 'fetch_user') {
        $userId = (int) $requestXml->user_id;
        
        $SQL_Q = "SELECT username, email, active, role FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['user_id' => $userId]);
        $user = $stmt->fetch();
        
        if ($user) {
            $response->addChild('status', 'success');
            $userNode = $response->addChild('user');
            $userNode->addChild('username', htmlspecialchars($user['username']));
            $userNode->addChild('email', htmlspecialchars($user['email']));
            $userNode->addChild('role', htmlspecialchars($user['role']));
            $userNode->addChild('active', $user['active']);
            echo $response->asXML();
        } else {
            $response->addChild('status', 'error');
            $response->addChild('message', 'User not found.');
            echo $response->asXML();
        }
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'Invalid request.');
        echo $response->asXML();

    }
} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
  }
?>
