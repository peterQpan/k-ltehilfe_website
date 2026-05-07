<?php
declare(strict_types=1);

header('Content-Type: image/jpeg');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['url']) || empty($_GET['url'])) {
    http_response_code(400);
    exit('No URL provided');
}

$imageUrl = trim((string)$_GET['url']);
$parts = parse_url($imageUrl);
$host = strtolower($parts['host'] ?? '');

if (
    $parts === false ||
    ($parts['scheme'] ?? '') !== 'https' ||
    !preg_match('/^scontent-[a-z0-9-]+\.cdninstagram\.com$/', $host)
) {
    http_response_code(400);
    exit('Invalid URL');
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

$imageData = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: 'image/jpeg';
curl_close($ch);

if ($httpCode === 200 && $imageData !== false) {
    header('Content-Type: ' . $contentType);
    echo $imageData;
} else {
    http_response_code(404);
    exit('Image not found');
}
?>
