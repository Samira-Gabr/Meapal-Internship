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
    }
    $xmlInput = file_get_contents('php://input');
    $requestxml = simplexml_load_string($xmlInput);
    $action = (string)$requestxml->action;
    $user_id = (string)$requestxml->user_id;

    $response = new SimpleXMLElement('<response/>');
    
    if ($action === 'fetch_user_info') {
        $SQL_Q = "SELECT username, email, profile_pic, role FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch();

        if ($user) {
            $response->addChild('status', 'success');
            $response->addChild('username', $user['username']);
            $response->addChild('email', $user['email']);
            $response->addChild('role', $user['role']);
            $response->addChild('profile_pic', $user['profile_pic']);
        } else {
            $response->addChild('status', 'error');
            $response->addChild('message', 'user not found');
        }
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'invalid action');
    }
    echo $response->asXML();
} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}
?>