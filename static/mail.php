<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$to = 'info@ingos-kleine-kältehilfe.de';

function sanitize($value) {
    return trim(filter_var($value, FILTER_SANITIZE_STRING));
}

$message = "Neue Nachricht von der Webseite:\n\n";
foreach ($_POST as $key => $value) {
    $message .= ucfirst(str_replace('-', ' ', $key)) . ': ' . sanitize($value) . "\n";
}

$subject = 'Website Form Submission';
if (!empty($_POST['volunteer-subject'])) {
    $subject = sanitize($_POST['volunteer-subject']);
} elseif (isset($_POST['subscribe-email'])) {
    $subject = 'Newsletter Anmeldung';
} elseif (isset($_POST['message'])) {
    $subject = 'Kontaktformular';
}

$headers = "From: info@ingos-kleine-kältehilfe.de\r\n";
if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $headers .= 'Reply-To: ' . sanitize($_POST['email']) . "\r\n";
} elseif (isset($_POST['volunteer-email']) && filter_var($_POST['volunteer-email'], FILTER_VALIDATE_EMAIL)) {
    $headers .= 'Reply-To: ' . sanitize($_POST['volunteer-email']) . "\r\n";
} elseif (isset($_POST['subscribe-email']) && filter_var($_POST['subscribe-email'], FILTER_VALIDATE_EMAIL)) {
    $headers .= 'Reply-To: ' . sanitize($_POST['subscribe-email']) . "\r\n";
}
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$mailSent = mail($to, $subject, $message, $headers, '-f info@ingos-kleine-kältehilfe.de');

if ($mailSent) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Fehler beim Senden';
}
?>
