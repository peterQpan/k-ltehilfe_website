<?php
header('Content-Type: image/jpeg');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['url']) || empty($_GET['url'])) {
    http_response_code(400);
    exit('No URL provided');
}

$imageUrl = $_GET['url'];

// Validiere dass es eine Instagram-URL ist
if (!preg_match('/^https:\/\/scontent-[a-z0-9-]+\.cdninstagram\.com\//', $imageUrl)) {
    http_response_code(400);
    exit('Invalid URL');
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$imageData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $imageData !== false) {
    echo $imageData;
} else {
    http_response_code(404);
    exit('Image not found');
}
?>
