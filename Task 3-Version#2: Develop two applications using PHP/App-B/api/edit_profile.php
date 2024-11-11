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

    $response = new SimpleXMLElement('<response/>');

    if ($action == 'fetch_all_user') {
        $userID = (string)$requestxml->user_id; 
        $SQL_Q = "SELECT * FROM users WHERE id = :id"; 
        $stmt = $pdo->prepare($SQL_Q);
        $stmt->execute(['id' => $userID]);
        $user = $stmt->fetch();

        if ($user) {
            $userNode = $response->addChild('user');
            $userNode->addChild('username', $user['username']);
            $userNode->addChild('id', $user['id']);
            $userNode->addChild('email', $user['email']);
            $userNode->addChild('profile_pic', $user['profile_pic']);
        } else {
            $response->addChild('status', 'error');
            $response->addChild('message', 'User not found');
        }
    } elseif ($action == 'update_user') {
        $userID = (int)$requestxml->user_id;
        $profile_pic = (string)$requestxml->profile_pic;
        $username = (string)$requestxml->username;
        $password = (string)$requestxml->password;
        $email = (string)$requestxml->email;

        $SQL_Q = "UPDATE users SET username = :username, profile_pic = :profile_pic, email = :email, password = :password WHERE id = :id";
        $stmt = $pdo->prepare($SQL_Q);
        $success = $stmt->execute([
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'profile_pic' => $profile_pic,
            'id' => $userID
        ]);
        if ($success) {
            $response->addChild('status', 'success');
        } else {
            $response->addChild('status', 'error');
            $response->addChild('message', 'Failed to update user');
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