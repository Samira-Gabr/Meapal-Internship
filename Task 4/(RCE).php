<?php
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    echo "Excuting: " . htmlspecialchars($cmd) . "<br>";
    system($cmd);
} else {
    echo "no command provided";
}
// http://example.com/vulnerable.php?cmd=ls

?>

<?php
$UserInput   = 'ls; rm -rf';
$SecureInput = escapeshellcmd("echo $UserInput");
echo shell_exec($SecureInput);
?>

<?php
$UserInput   = 'file.txt; rm -rf/';
$SecureInput = escapeshellarg($UserInput);
$output      = "cat $SecureInput";
echo shell_exec($output);
?>

Serialize 
<?php
$user     = ['username' => 'admin', 'password' => 'P@ssw0rd123'];
$JsonData = json_encode($user);
file_put_contents('user_data.json', $JsonData);
echo "Serialized data Saved ;)";
?>
Deserialize 
<?php
$JsonData = file_get_contents('user_data.json');
$user     = json_decode($JsonData, true);
echo "Username: " . htmlspecialchars($user['username']);
echo "Password: " . htmlspecialchars($user['password']);
?>