<?php
// mail.php - handles form submissions and forwards them via email

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Helper functions
function sanitize_text($key) {
    return trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

function sanitize_email($key) {
    return trim(filter_input(INPUT_POST, $key, FILTER_VALIDATE_EMAIL));
}

$to = 'info@ingos-kleine-kältehilfe.de';
$subject = 'Webseitenanfrage';
$message = '';
$replyTo = '';

if (sanitize_email('volunteer-email')) {
    // Volunteer form
    $name = sanitize_text('volunteer-name');
    $email = sanitize_email('volunteer-email');
    $volSubject = sanitize_text('volunteer-subject');
    $subject = 'Ehrenamtliche Anfrage: ' . $volSubject;
    $message = "Name: {$name}\nE-Mail: {$email}\nBetreff: {$volSubject}\n";
    $replyTo = $email;
} elseif (sanitize_email('subscribe-email')) {
    // Newsletter form
    $email = sanitize_email('subscribe-email');
    $subject = 'Newsletter Anmeldung';
    $message = "E-Mail: {$email}\nMöchte den Newsletter erhalten.";
    $replyTo = $email;
} else {
    // Contact form
    $name = sanitize_text('first-name');
    $email = sanitize_email('email');
    $msg = sanitize_text('message');
    $subject = 'Kontaktformular Nachricht';
    $message = "Name: {$name}\nE-Mail: {$email}\nNachricht:\n{$msg}";
    $replyTo = $email;
}

$headers = [];
$headers[] = 'From: info@ingos-kleine-kältehilfe.de';
if ($replyTo) {
    $headers[] = 'Reply-To: ' . $replyTo;
}
$headers[] = 'X-Mailer: PHP/' . phpversion();

$success = mail($to, $subject, $message, implode("\r\n", $headers), '-f info@ingos-kleine-kältehilfe.de');

if ($success) {
    http_response_code(200);
    echo 'OK';
} else {
    http_response_code(500);
    echo 'ERROR';
}
?>
