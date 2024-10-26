<?php

$host = 'localhost';
$dbname = 'profile_management';
$user = 'root' ;
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(pdo::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $E) {
    die ("DataBase Connection FAILED ! :" . $E->getmessage());
}
?>