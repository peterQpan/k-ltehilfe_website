<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple mail handler for form submissions
// Sends collected form data to info@ingos-kleine-kaeltehilfe.de
// Jetzt via PHPMailer über Strato SMTP (statt PHP mail())

header('Content-Type: text/plain; charset=utf-8');

// Logging-Funktion
function log_message($message) {
    error_log(date('Y-m-d H:i:s') . ' - ' . $message);
}

// --- PHPMailer Setup (Autoload) ---
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    log_message('Autoload nicht gefunden');
    http_response_code(500);
    echo 'Serverfehler: Autoload nicht gefunden (vendor/autoload.php).';
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load environment variables from .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    log_message('.env geladen');
} else {
    log_message('.env nicht gefunden');
}

$recipient = 'info@ingos-kleine-kaeltehilfe-saarbruecken.de';
$from = 'info@ingos-kleine-kaeltehilfe-saarbruecken.de';

function clean($value) {
    return trim(htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    log_message('Falsche Methode: ' . ($_SERVER['REQUEST_METHOD'] ?? 'none'));
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

log_message('POST empfangen: ' . json_encode($_POST));

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
    log_message('Ehrenamt-Form verarbeitet');
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
    log_message('Kontakt-Form verarbeitet');
// Newsletter Subscription
} elseif (!empty($_POST['subscribe-email'])) {
    $email = clean($_POST['subscribe-email']);
    $subject = 'Newsletter Anmeldung';
    $body .= "Newsletter Anmeldung\n";
    $body .= "E-Mail: $email\n";
    $replyTo = $email;
    log_message('Newsletter-Form verarbeitet');
} else {
    log_message('Ungültige Formulardaten');
    http_response_code(400);
    echo 'Ungültige Formulardaten';
    exit;
}

// --- Versand via PHPMailer/SMTP (Strato) ---
$smtpHost = $_ENV['SMTP_HOST'] ?? 'smtp.strato.de';
$smtpPort = (int)($_ENV['SMTP_PORT'] ?? 587);
$smtpUser = $_ENV['SMTP_USER'] ?? $from;
$smtpPass = $_ENV['SMTP_PASS'] ?? '';
$smtpDebug = ($_ENV['MAIL_DEBUG'] ?? 0) ? 2 : 0;

log_message("SMTP Konfig: Host=$smtpHost, Port=$smtpPort, User=$smtpUser, Pass=" . (empty($smtpPass) ? 'leer' : 'gesetzt'));

if (!$smtpPass) {
    log_message('SMTP_PASS nicht gesetzt');
    http_response_code(500);
    echo 'Serverfehler: SMTP_PASS nicht gesetzt.';
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $smtpPort;
    $mail->SMTPDebug = $smtpDebug;

    $mail->setFrom($from, 'Ingos kleine Kältehilfe Website');
    $mail->addAddress($recipient);
    if (!empty($replyTo)) {
        $mail->addReplyTo($replyTo);
    }

    $mail->CharSet = 'UTF-8';
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
    log_message('Mail erfolgreich gesendet');
    echo 'OK';
} catch (Exception $e) {
    log_message('Fehler beim Versand: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo 'Fehler beim Versand';
}
?>
