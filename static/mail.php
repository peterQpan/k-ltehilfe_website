<?php
// Simple mail handler for contact, volunteer and newsletter forms
// Sends form data to info@ingos-kleine-kältehilfe.de

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$to = 'info@ingos-kleine-kältehilfe.de';
$from = 'info@ingos-kleine-kältehilfe.de';
$subject = 'Webformular';
$message = '';
$replyTo = $from;

// Kontaktformular
if (isset($_POST['first-name'], $_POST['email'], $_POST['message'])) {
    $name = trim(filter_var($_POST['first-name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $msg = trim(filter_var($_POST['message'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subject = "Kontaktformular - $name";
    $message = "Name: $name\nE-Mail: $email\nNachricht:\n$msg";
    if ($email) {
        $replyTo = $email;
    }
// Ehrenamtliches Mithelfen
} elseif (isset($_POST['volunteer-name'], $_POST['volunteer-email'], $_POST['volunteer-subject'])) {
    $name = trim(filter_var($_POST['volunteer-name'], FILTER_SANITIZE_SPECIAL_CHARS));
    $email = filter_var($_POST['volunteer-email'], FILTER_VALIDATE_EMAIL);
    $volSubject = trim(filter_var($_POST['volunteer-subject'], FILTER_SANITIZE_SPECIAL_CHARS));
    $subject = "Mithelfen - $name";
    $message = "Name: $name\nE-Mail: $email\nBetreff: $volSubject";
    if ($email) {
        $replyTo = $email;
    }
// Newsletter-Anmeldung
} elseif (isset($_POST['subscribe-email'])) {
    $email = filter_var($_POST['subscribe-email'], FILTER_VALIDATE_EMAIL);
    $subject = 'Newsletter-Anmeldung';
    $message = "E-Mail: $email möchte den Newsletter erhalten.";
    if ($email) {
        $replyTo = $email;
    }
} else {
    http_response_code(400);
    exit('Ungültige Anfrage');
}

$headers = "From: $from\r\n";
$headers .= "Reply-To: $replyTo\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers, "-f $from")) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Fehler beim Senden';
}
