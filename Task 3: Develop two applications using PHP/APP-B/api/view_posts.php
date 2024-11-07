<?php
require 'dbconn.php';
header('Content-Type: application/xml');
define('API_TOKEN', 'Token 2#7#7#2#0#0#0');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $response = new SimpleXMLElement('<response/>');
    
    $SQL_Q = "SELECT posts.*, users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.approved = 1";
    $stmt  = $pdo->prepare($SQL_Q);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    
    if ($posts) {
        $response->addChild('status', 'success');
        foreach ($posts as $post) {
            $postxml = $response->addChild('post');
            $postxml->addChild('title', htmlspecialchars($post['title']));
            $postxml->addChild('username', htmlspecialchars($post['username']));
            $postxml->addChild('content', htmlspecialchars($post['content']));
            if ($post['image']) {
                $postxml->addChild('image', htmlspecialchars($post['image']));
            }
        }
    } else {
        $response->addChild('status', 'error');
        $response->addChild('message', 'No posts available.');
    }
    echo $response->asXML();

} else {
    $response = new SimpleXMLElement('<response/>');
    $response->addChild('status', 'error');
    $response->addChild('message', 'Invalid request method');
    echo $response->asXML();
}
?>