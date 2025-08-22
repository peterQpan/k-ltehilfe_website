<?php
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$to = 'info@ingos-kleine-kältehilfe.de';
$from = 'info@ingos-kleine-kältehilfe.de';
$replyTo = $from;
$subject = 'Kontaktanfrage';
$message = '';

function sanitize_text($text) {
    return trim(strip_tags($text));
}

if (isset($_POST['subscribe-email'])) {
    $email = filter_var($_POST['subscribe-email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        http_response_code(400);
        exit('Invalid email');
    }
    $subject = 'Newsletter-Anmeldung';
    $message = "Neue Newsletter-Anmeldung:\nEmail: $email";
    $replyTo = $email;
} elseif (isset($_POST['volunteer-email'])) {
    $name = sanitize_text($_POST['volunteer-name'] ?? '');
    $email = filter_var($_POST['volunteer-email'], FILTER_VALIDATE_EMAIL);
    $volSubject = sanitize_text($_POST['volunteer-subject'] ?? '');
    if (!$name || !$email) {
        http_response_code(400);
        exit('Invalid input');
    }
    $subject = 'Ehrenamtliche Anfrage';
    $message = "Name: $name\nEmail: $email\nBetreff: $volSubject";
    $replyTo = $email;
} elseif (isset($_POST['email'])) {
    $name = sanitize_text($_POST['first-name'] ?? '');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $msg = sanitize_text($_POST['message'] ?? '');
    if (!$name || !$email) {
        http_response_code(400);
        exit('Invalid input');
    }
    $subject = 'Kontaktformular';
    $message = "Name: $name\nEmail: $email\nNachricht:\n$msg";
    $replyTo = $email;
} else {
    http_response_code(400);
    exit('No valid form data');
}

$headers = 'From: ' . $from . "\r\n" .
           'Reply-To: ' . $replyTo . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers, '-f ' . $from)) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Error sending email';
}
