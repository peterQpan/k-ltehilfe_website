<?php
// Simple mail handler for form submissions
// Sends collected form data to info@ingos-kleine-kaeltehilfe.de
// Jetzt via PHPMailer über Strato SMTP (statt PHP mail())

header('Content-Type: text/plain; charset=utf-8');

// --- PHPMailer Setup (Autoload) ---
// Erwartet einen Composer-Autoload in ./vendor. Fallback auf ../vendor.
// Der Build legt bei Deployment "mail.php" und "vendor/" ins gleiche Verzeichnis (z. B. /public auf Strato).
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    http_response_code(500);
    echo 'Serverfehler: Autoload nicht gefunden (vendor/autoload.php).';
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$recipient = 'info@ingos-kleine-kaeltehilfe.de'; // Empfänger (internes Postfach)
$from = 'info@ingos-kleine-kaeltehilfe.de';       // Absender MUSS Domain-Adresse sein (Strato-Anforderung)

function clean($value) {
    return trim(htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
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

// --- Versand via PHPMailer/SMTP (Strato) ---
$smtpHost = getenv('SMTP_HOST') ?: 'smtp.strato.de';
$smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
$smtpUser = getenv('SMTP_USER') ?: $from; // Standard: From-Adresse
$smtpPass = getenv('SMTP_PASS') ?: '';
$smtpDebug = getenv('MAIL_DEBUG') ? 2 : 0; // MAIL_DEBUG=1 aktiviert Debug-Ausgaben in Server-Logs

if (!$smtpPass) {
    http_response_code(500);
    echo 'Serverfehler: SMTP_PASS nicht gesetzt.';
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Strato: 587 + STARTTLS
    $mail->Port = $smtpPort;
    $mail->SMTPDebug = $smtpDebug;

    // Absender/Empfänger
    $mail->setFrom($from, 'Ingos kleine Kältehilfe Website');
    $mail->addAddress($recipient);
    if (!empty($replyTo)) {
        // PHPMailer validiert intern; bei Ungültigkeit wird Ausnahme geworfen
        $mail->addReplyTo($replyTo);
    }

    // Inhalt
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Fehler beim Versand';
}
?>
