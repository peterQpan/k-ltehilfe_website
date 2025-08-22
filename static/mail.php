<?php
// Simple mail handler for form submissions
// Sends collected form data to info@ingos-kleine-kaeltehilfe.de

header('Content-Type: text/plain; charset=utf-8');

$recipient = 'info@ingos-kleine-kaeltehilfe.de'; // Should be the same domain to pass Strato checks
$from = 'info@ingos-kleine-kaeltehilfe.de';

function clean($value) {
    return trim(htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$subject = 'Website Kontakt';
$body = '';
$replyTo = '';

// Ehrenamt / Volunteer form
if (!empty($_POST['volunteer-name']) && !empty($_POST['volunteer-email'])) {
    $name = clean($_POST['volunteer-name']);
    $email = clean($_POST['volunteer-email']);
    $subject = clean($_POST['volunteer-subject'] ?? 'Ehrenamtliche Anfrage');
    $body .= "Ehrenamtliche Anfrage\n";
    $body .= "Name: $name\n";
    $body .= "E-Mail: $email\n";
    $body .= "Betreff: $subject\n";
    $replyTo = $email;
// Kontaktformular
} elseif (!empty($_POST['first-name']) && !empty($_POST['email'])) {
    $name = clean($_POST['first-name']);
    $email = clean($_POST['email']);
    $message = clean($_POST['message'] ?? '');
    $subject = 'Kontaktformular Nachricht';
    $body .= "Kontaktformular Nachricht\n";
    $body .= "Name: $name\n";
    $body .= "E-Mail: $email\n\n";
    $body .= "Nachricht:\n$message\n";
    $replyTo = $email;
// Newsletter Subscription
} elseif (!empty($_POST['subscribe-email'])) {
    $email = clean($_POST['subscribe-email']);
    $subject = 'Newsletter Anmeldung';
    $body .= "Newsletter Anmeldung\n";
    $body .= "E-Mail: $email\n";
    $replyTo = $email;
} else {
    http_response_code(400);
    echo 'Ungültige Formulardaten';
    exit;
}

$headers = "From: $from\r\n";
if ($replyTo) {
    $headers .= "Reply-To: $replyTo\r\n";
}
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

if (mail($recipient, $subject, $body, $headers, '-f '.$from)) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Fehler beim Versand';
}
?>
