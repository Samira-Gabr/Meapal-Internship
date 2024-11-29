<?php
  $file = $_GET['file'];
  include($file . '.php');
?>

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

// Sanitize User Input
$UserInput = $_GET['page'] ?? 'home';
$UserInput = str_replace(['../', './', '..\\', '.\\', '/'], '', $UserInput );
include "pages/$UserInput.php";


// Use Basename()
$UserInput = $_GET['page'];
$UserInput = basename($UserInput); 
include "pages/$UserInput.php";

//Restrict File Inclusion to a Specific Directory
$UserInput     = $_GET['page'] ?? 'home';
$PathOfRequest = realpath("pages/$UserInput.php");
$ServerPath    = realpath("pages");
if (strpos($PathOfRequest, $ServerPath) === 0 && file_exists($PathOfRequest)) {
    include $PathOfRequest;
} else {
    echo " Invaid page";
    exit;
}
//Logical Mapping
$FileMap = ['home'    => 'pages/home.php',
            'about'   => 'pages/about.php',
            'contact' => 'pages/contact.php'];
$UserInput = $_GET['page'] ?? 'home';
if (isset($FileMap[$UserInput])) {
    include $FileMap[$UserInput];
} else {
    echo "Invalid Page";
    exit;
}
?>