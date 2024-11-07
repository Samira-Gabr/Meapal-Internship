<?php
require 'dbconn.php';
header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $xmlInput = file_get_contents('php://input');
    $requestxml = simplexml_load_string($xmlInput);
    $action = (string)$requestxml->action;
    $userID = (string)$requestxml->user_id;

    $response = new SimpleXMLElement('<response/>');

    $SQL_Q = "SELECT id, username, email, active FROM users";
    $stmt = $pdo->query($SQL_Q);
    $users = $stmt->fetchAll();

    foreach ($users as $user) {
        $userXML = $response->addChild('user');
        $userXML->addChild('id', $user['id']);
        $userXML->addChild('username', $user['username']);
        $userXML->addChild('email', $user['email']);
        $userXML->addChild('active', $user['active']);
    }

    if ($action === 'delete') {
        $SQL_Q = "DELETE FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($SQL_Q);
        $result = $stmt->execute(['user_id' => $userID]);
        if ($result) {
            $response->addChild('status', 'success');
        } else {
            $response->addChild('status', 'error');
            $response->addChild('message', 'Failed to delete user');
        }
    } elseif ($action === 'toggle_status') {
        $SQL_Q = "UPDATE users SET active = NOT active WHERE id = :user_id";
        $stmt = $pdo->prepare($SQL_Q);
        $result = $stmt->execute(['user_id' => $userID]);
        if ($result) {
            $response->addChild('status', 'success');
        } else {
            $response->addChild('status', 'error');
            $response->addChild('message', 'Failed to toggle user status');
        }
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'Invalid action');
    }
    echo $response->asXML();
} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}

?>