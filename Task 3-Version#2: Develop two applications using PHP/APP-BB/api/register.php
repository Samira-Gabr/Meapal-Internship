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
    $xmlReceived = file_get_contents('php://input');
    if (!$xmlReceived) {
      echo "No XML data received :(";
      exit();
    }
    $xml = simplexml_load_string($xmlReceived);
    $username = (string)$xml->username;
    $email = (string)$xml->email;
    $password = (string)$xml->password;
    $profile_pic = (string)$xml->profile_pic;
    $role = (string)$xml->role;
    /*------------------------------------------*/
    $SQL_Q = "INSERT INTO users (username, email, password, profile_pic, role) VALUES (:username, :email, :password, :profile_pic, :role)";
    $stmt = $pdo->prepare($SQL_Q);
    $result = $stmt->execute([
        'username' => $username,
        'email' => $email, 
        'password' => $password,
        'profile_pic' => $profile_pic,
        'role' => $role
     ]);
     $response = new SimpleXMLElement('<response/>');
     if ($result) {
      $response->addChild('status', 'success');
      $response->addChild('message', 'User registered successfully ;)');
      echo $response->asXML();
      } else {
      $response->addChild('status', 'error');
      $response->addChild('message', 'Failed to register these user :( ');
      echo $response->asXML();
  }
} else {
  $response = new SimpleXMLElement('<response/>');
  $response->addChild('status', 'error');
  $response->addChild('message', 'Invalid request method');
  echo $response->asXML();
}
?>
