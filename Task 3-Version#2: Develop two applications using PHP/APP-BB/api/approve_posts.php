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
    $postId = (int)$requestxml->post_id;

    $response = new SimpleXMLElement('<response/>');

    $SQL_Q = "SELECT posts.*, users.username FROM posts INNER JOIN users ON posts.user_id = users.id";
    $stmt = $pdo->query($SQL_Q);
    $posts = $stmt->fetchAll();

    foreach ($posts as $post) {
        $postXml = $response->addChild('post');
        $postXml->addChild('id', $post['id']);
        $postXml->addChild('title', $post['title']);
        $postXml->addChild('content', $post['content']);
        $postXml->addChild('username', $post['username']);
        $postXml->addChild('approved', $post['approved']);
    }

    if ($action === 'approve') {
        $SQL_Q = "UPDATE posts SET approved = 1 WHERE id = :post_id";
    } elseif ($action === 'disapprove') {
        $SQL_Q = "UPDATE posts SET approved = 0 WHERE id = :post_id";
    } elseif ($action === 'delete') {
        $SQL_Q = "DELETE FROM posts WHERE id = :post_id";
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'Invalid Action');
        echo $response->asXML();
        exit();
    }
    $stmt = $pdo->prepare($SQL_Q);
    $result = $stmt->execute(['post_id' => $postId]);
    if ($result) {
        $response->addChild('status', 'success');
    } else {
        $response->addChild('status', 'error');
    }
    //$response->addChild('status', $result ? 'success' : 'error');
    echo $response->asXML();
} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}
?>
