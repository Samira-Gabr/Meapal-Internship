<?php
// code that is vulnerable to SSRF //
$Url = $_GET['url'] ?? '';

if ($Url) {
    $response = file_get_contents($Url);
    echo "Response from URL: " . nl2br(htmlspecialchars($response));
} else {
    echo "Please Provide valid URL";
}
//http://example.com/vulnerable-script.php?url=http://127.0.0.1:8080/admin//
?> 


<?php
$url = $_GET['url'] ?? '';

if ($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        die("Invalid URL ");
    }

    $parsedURL = parse_url($url);
    if (!$parsedURL || !isset($parsedURL['host'], $parsedURL['scheme'])) {
        die("Malformed URL");
    }

    $allowedScheme = ['http', 'https'];
    if (!in_array($parsedURL['scheme'], $allowedScheme, true)) {
        die ("URL scheme no allowed");
    }

    $allowedDomain = ['example.com', 'api.example.com'];
    if (!in_array($parsedUrl['host'], $allowedDomains, true)) {
    die("Domain not allowed.");
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
}

?>