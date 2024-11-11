<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$logFilePath = 'log.txt';

if (file_exists($logFilePath)) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="log.txt"');
    header('Content-Length: ' . filesize($logFilePath));
    readfile($logFilePath);
    exit();
} else {
    echo "Log file not found.";
}
