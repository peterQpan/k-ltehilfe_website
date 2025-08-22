<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$to = 'info@xn--ingos-kleine-kltehilfe-e5b.de'; // info@ingos-kleine-kältehilfe.de
$fields = [];
foreach ($_POST as $key => $value) {
    if (is_array($value)) {
        continue;
    }
    $fields[$key] = trim(filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

if (isset($fields['volunteer-email'])) {
    $subject = 'Neue Helfer-Anfrage';
    $replyTo = $fields['volunteer-email'];
} elseif (isset($fields['subscribe-email'])) {
    $subject = 'Newsletter-Anmeldung';
    $replyTo = $fields['subscribe-email'];
} else {
    $subject = 'Neue Kontaktanfrage';
    $replyTo = $fields['email'] ?? $to;
}

$message = "Folgende Daten wurden übermittelt:\n\n";
foreach ($fields as $k => $v) {
    $label = ucfirst(str_replace('-', ' ', $k));
    $message .= "$label: $v\n";
}

$headers = 'From: ' . $to . "\r\n" .
           'Reply-To: ' . $replyTo . "\r\n" .
           "Content-Type: text/plain; charset=utf-8\r\n";

$success = mail($to, $subject, $message, $headers, '-f' . $to);

if ($success) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Mail send failed']);
}
?>
