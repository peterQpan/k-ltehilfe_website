<?php
require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: text/plain; charset=utf-8');

$recipient = 'info@ingos-kleine-kältehilfe.de';

// Determine which form submitted
$subject = '';
$body = '';

if (!empty($_POST['subscribe-email'])) {
    $email = filter_var($_POST['subscribe-email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        http_response_code(400);
        echo 'Invalid email';
        exit;
    }
    $subject = 'Newsletter Anmeldung';
    $body = "Neue Newsletter-Anmeldung: $email";
} elseif (!empty($_POST['volunteer-email'])) {
    $name = filter_var($_POST['volunteer-name'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['volunteer-email'], FILTER_VALIDATE_EMAIL);
    $volSubject = filter_var($_POST['volunteer-subject'] ?? 'Ich möchte mithelfen!', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!$email) {
        http_response_code(400);
        echo 'Invalid email';
        exit;
    }
    $subject = 'Mithelfen Formular';
    $body = "Name: $name\nE-Mail: $email\nBetreff: $volSubject";
} elseif (!empty($_POST['email'])) {
    $name = filter_var($_POST['first-name'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!$email) {
        http_response_code(400);
        echo 'Invalid email';
        exit;
    }
    $subject = 'Kontaktformular';
    $body = "Name: $name\nE-Mail: $email\nNachricht:\n$message";
} else {
    http_response_code(400);
    echo 'No data';
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.strato.de';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER') ?: 'info@ingos-kleine-kältehilfe.de';
    $mail->Password = getenv('SMTP_PASS') ?: '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($mail->Username, 'Ingos kleine Kältehilfe');
    $mail->addAddress($recipient);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Mailer Error';
}
