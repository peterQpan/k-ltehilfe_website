<?php
header('Content-Type: application/json');

$username = 'ingoskleinekaeltehilfe';
$endpoint = 'https://www.instagram.com/' . $username . '/?__a=1&__d=dis';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36');

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$images = [];
if (isset($data['graphql']['user']['edge_owner_to_timeline_media']['edges'])) {
    foreach ($data['graphql']['user']['edge_owner_to_timeline_media']['edges'] as $edge) {
        if (isset($edge['node']['thumbnail_src'])) {
            $images[] = $edge['node']['thumbnail_src'];
        }
        if (count($images) >= 12) {
            break;
        }
    }
}

echo json_encode($images);
