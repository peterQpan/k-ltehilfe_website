<?php
header('Content-Type: application/json');

$ch = curl_init('https://www.instagram.com/api/v1/users/web_profile_info/?username=ingoskleinekaeltehilfe');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode([]);
    exit;
}
curl_close($ch);
$data = json_decode($result, true);
$edges = [];
if (isset($data['data']['user']['edge_owner_to_timeline_media']['edges'])) {
    $edges = $data['data']['user']['edge_owner_to_timeline_media']['edges'];
} elseif (isset($data['graphql']['user']['edge_owner_to_timeline_media']['edges'])) {
    $edges = $data['graphql']['user']['edge_owner_to_timeline_media']['edges'];
}
$images = [];
foreach ($edges as $edge) {
    if (isset($edge['node']['display_url'])) {
        $images[] = $edge['node']['display_url'];
    } elseif (isset($edge['node']['thumbnail_src'])) {
        $images[] = $edge['node']['thumbnail_src'];
    }
    if (count($images) >= 15) {
        break;
    }
}
echo json_encode($images);
