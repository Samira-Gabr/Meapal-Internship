<?php
require 'dbconn.php';
header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $header = getallheaders();
    if (!isset($header['Authorization']) || $header['Authorization'] !== API_TOKEN) {
    /*------------------------------------*/
     $response = new SimpleXMLElement('<response/>');
     $response->addChild('status', 'error');
     $response->addChild('message', 'Invalid API_token :(');
     echo $response->asXML();
     exit();
    }
    $xmlRecived = file_get_contents('php://input');
    $xml = simplexml_load_string($xmlRecived);
    $login = (string)$xml->login;
    $password =(string)$xml->password;
    /*---------------------------------*/
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $SQL_Q = "SELECT * FROM users WHERE email = :login";
    } else {
        $SQL_Q = "SELECT * FROM users WHERE username = :login";
    }
    $stmt = $pdo->prepare($SQL_Q);
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();
    /*---------------------------------------------- */
    $response = new SimpleXMLElement('<response/>');
    if ($user && password_verify($password, $user['password'])) {
        //$response = new SimpleXMLElement('<response/>');
        $response->addChild('status','success');
        $response->addChild('user_id', $user['id']);
        $response->addChild('username', $user['username']);
        $response->addChild('role', $user['role']);
        //echo $response->asXML();
    } else {
        //$response = new SimpleXMLElement('</response>');
        $response->addChild('status', 'error');
        $response->addChild('message', 'Invalid credentials.');
        //echo $response->asXML();
    }
    echo $response->asXML();

} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}
?>