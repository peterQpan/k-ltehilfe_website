<?php
header('Content-Type: application/json');

$username = 'ingoskleinekaeltehilfe';
$endpoint = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=' . $username;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_ENCODING, ''); // Automatische Dekompression
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'x-ig-app-id: 936619743392459',
    'Accept-Language: en-US,en;q=0.9',
    'Accept-Encoding: gzip, deflate, br',
    'Accept: */*'
));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$images = [];
if (isset($data['data']['user']['edge_owner_to_timeline_media']['edges'])) {
    foreach ($data['data']['user']['edge_owner_to_timeline_media']['edges'] as $edge) {
        if (isset($edge['node']['display_url'])) {
            $images[] = $edge['node']['display_url'];
        }
        if (count($images) >= 15) {
            break;
        }
    }
}

echo json_encode($images);
