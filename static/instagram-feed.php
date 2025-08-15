<?php
header('Content-Type: application/json');

$user = 'ingoskleinekaeltehilfe';
$url = "https://www.instagram.com/api/v1/users/web_profile_info/?username={$user}";

$opts = [
    'http' => [
        'header' => "User-Agent: Mozilla/5.0\r\nX-IG-App-ID: 936619743392459\r\n"
    ]
];
$context = stream_context_create($opts);

$json = @file_get_contents($url, false, $context);
if (!$json) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to fetch data']);
    exit;
}

$data = json_decode($json, true);
if (!isset($data['data']['user']['edge_owner_to_timeline_media']['edges'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid response']);
    exit;
}

$edges = $data['data']['user']['edge_owner_to_timeline_media']['edges'];
$items = array_slice($edges, 0, 15);
$result = [];

foreach ($items as $edge) {
    $node = $edge['node'];
    $result[] = [
        'thumbnail' => $node['thumbnail_src'] ?? '',
        'link' => 'https://www.instagram.com/p/' . ($node['shortcode'] ?? '') . '/',
    ];
}

echo json_encode($result);
