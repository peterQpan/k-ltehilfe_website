<?php
header('Content-Type: application/json');
$username = 'ingoskleinekaeltehilfe';
$url = "https://www.instagram.com/$username/?__a=1&__d=dis";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36');
$data = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($httpCode !== 200 || !$data) {
    echo json_encode(['error' => 'Unable to retrieve feed']);
    exit;
}
$json = json_decode($data, true);
$edges = [];
if (isset($json['graphql']['user']['edge_owner_to_timeline_media']['edges'])) {
    $edges = $json['graphql']['user']['edge_owner_to_timeline_media']['edges'];
} elseif (isset($json['data']['user']['edge_owner_to_timeline_media']['edges'])) {
    // Alternate structure
    $edges = $json['data']['user']['edge_owner_to_timeline_media']['edges'];
}
$result = [];
foreach (array_slice($edges, 0, 15) as $edge) {
    $node = $edge['node'];
    $result[] = [
        'thumbnail' => $node['thumbnail_src'] ?? $node['display_url'] ?? '',
        'url' => 'https://www.instagram.com/p/' . ($node['shortcode'] ?? '') . '/',
    ];
}
echo json_encode($result);
?>
