<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = 'ingoskleinekaeltehilfe';
$endpoint = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=' . $username;

echo "Testing Instagram API...\n";
echo "Endpoint: $endpoint\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'x-ig-app-id: 936619743392459',
    'Accept-Language: en-US,en;q=0.9',
    'Accept-Encoding: gzip, deflate, br',
    'Accept: */*'
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $http_code\n";
echo "cURL Error: $error\n";
echo "Response length: " . strlen($response) . "\n";
echo "First 200 chars of response: " . substr($response, 0, 200) . "\n";

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg() . "\n";
} else {
    echo "JSON parsed successfully\n";
    if (isset($data['data']['user']['edge_owner_to_timeline_media']['edges'])) {
        echo "Found " . count($data['data']['user']['edge_owner_to_timeline_media']['edges']) . " posts\n";
    } else {
        echo "No posts found or wrong structure\n";
        echo "Available keys in data: " . implode(', ', array_keys($data)) . "\n";
    }
}
?>
