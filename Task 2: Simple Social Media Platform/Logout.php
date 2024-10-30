<?php
session_start();
$log_file = 'log.txt';
if ($_SESSION['user_id']) {
    file_put_contents($log_file, "User ID {$_SESSION['user_id']} logged out at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
}
session_unset();
session_destroy();
header("Location: login.php")
?>