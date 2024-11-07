<?php
require 'dbconn.php';
header('Content-Type: application/xml');

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
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'User not found.');
    }
} else {
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request.');
}

echo $response->asXML();
?>
