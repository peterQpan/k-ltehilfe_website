<?php
// Simple mail handler for form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$to = 'info@ingos-kleine-kältehilfe.de';
$from = 'info@ingos-kleine-kältehilfe.de';
$subject = 'Neue Formularnachricht';

// Build message body from POST data
$body = "Eingesandte Daten:\n\n";
foreach ($_POST as $key => $value) {
    $cleanKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
    $body .= ucfirst(str_replace('-', ' ', $cleanKey)) . ': ' . trim($value) . "\n";
}

$headers = "From: $from\r\n";
// Use reply-to if an email field was submitted
foreach (['email', 'volunteer-email', 'subscribe-email'] as $replyKey) {
    if (!empty($_POST[$replyKey])) {
        $headers .= 'Reply-To: ' . $_POST[$replyKey] . "\r\n";
        break;
    }
}

// Use envelope sender for STRATO servers
$mailSent = mail($to, $subject, $body, $headers, '-f' . $from);

if ($mailSent) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Mail konnte nicht gesendet werden.';
}
?>
