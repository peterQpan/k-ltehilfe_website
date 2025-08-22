<?php
$to = 'info@ingos-kleine-kältehilfe.de';
$from = 'info@ingos-kleine-kältehilfe.de';
ini_set('sendmail_from', $from);

$data = $_POST;
$subject = '';
$message = '';
$replyTo = '';

if (isset($data['first-name'], $data['email']) && isset($data['message'])) {
    $name = trim($data['first-name']);
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    $msg = trim($data['message']);
    if (!$email) {
        http_response_code(400);
        exit('Invalid email');
    }
    $subject = 'Kontaktformular';
    $message = "Name: $name\nEmail: $email\nNachricht:\n$msg\n";
    $replyTo = $email;
} elseif (isset($data['volunteer-name'], $data['volunteer-email'])) {
    $name = trim($data['volunteer-name']);
    $email = filter_var($data['volunteer-email'], FILTER_VALIDATE_EMAIL);
    $subj = trim($data['volunteer-subject'] ?? '');
    if (!$email) {
        http_response_code(400);
        exit('Invalid email');
    }
    $subject = 'Mithelfen Formular';
    if ($subj !== '') {
        $subject .= ': ' . $subj;
    }
    $message = "Name: $name\nEmail: $email\nBetreff: $subj\n";
    $replyTo = $email;
} elseif (isset($data['subscribe-email'])) {
    $email = filter_var($data['subscribe-email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        http_response_code(400);
        exit('Invalid email');
    }
    $subject = 'Newsletter Anmeldung';
    $message = "Neue Newsletter-Anmeldung: $email\n";
    $replyTo = $email;
} else {
    http_response_code(400);
    exit('No valid form data');
}

$headers = "From: $from\r\n";
if ($replyTo) {
    $headers .= "Reply-To: $replyTo\r\n";
}
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers, '-f' . $from)) {
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Error';
}
