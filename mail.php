<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)$_ENV['SMTP_PORT'];
    $mail->SMTPDebug  = (int)$_ENV['MAIL_DEBUG'];

    $mail->setFrom('info@ingos-kleine-kaeltehilfe.de');
    $mail->addAddress('info@ingos-kleine-kaeltehilfe.de');

    $mail->Subject = 'Kontaktformular';
    $mail->Body    = 'Dies ist eine Testmail.';

    $mail->send();
    echo "Mail wurde gesendet\n";
} catch (Exception $e) {
    echo "Mail konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}\n";
}
