<?php
$host = 'localhost';
$dbname = 'social_media2';
$user = 'root';
$password ='';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $E){
    die ("DataBase Connection FAILED! :" . $E->getmessage());
}
?>
