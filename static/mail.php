<?php
/**
 * Konsolidiertes E-Mail-Script für Ingos kleine Kältehilfe
 * Unterstützt: Newsletter, Kontakt und Ehrenamt-Formulare
 * Verwendet PHPMailer mit SMTP-Authentifizierung
 */

// Produktions-optimiertes Error Reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Content-Type abhängig von Accept-Header
$isJsonRequest = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
header('Content-Type: ' . ($isJsonRequest ? 'application/json' : 'text/plain') . '; charset=utf-8');

// Rate Limiting (einfache IP-basierte Implementierung)
session_start();
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateKey = 'mail_rate_' . md5($clientIP);
$lastRequest = $_SESSION[$rateKey] ?? 0;
$minInterval = 30; // 30 Sekunden zwischen Anfragen

if (time() - $lastRequest < $minInterval) {
    http_response_code(429);
    if ($isJsonRequest) {
        echo json_encode(['error' => 'Zu viele Anfragen. Bitte warten Sie 30 Sekunden.']);
    } else {
        echo 'Zu viele Anfragen. Bitte warten Sie 30 Sekunden.';
    }
    exit;
}
$_SESSION[$rateKey] = time();

// Logging-Funktion (datenschutzkonform)
function log_message($message) {
    $sanitized = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '[EMAIL_REDACTED]', $message);
    error_log(date('Y-m-d H:i:s') . ' [MAIL] ' . $sanitized);
}

// Response-Funktion
function send_response($success, $message, $code = 200) {
    global $isJsonRequest;
    http_response_code($success ? 200 : $code);
    
    if ($isJsonRequest) {
        echo json_encode($success ? ['success' => $message] : ['error' => $message]);
    } else {
        echo $success ? 'OK' : $message;
    }
    exit;
}

// --- PHPMailer Autoload (erweiterte Pfadsuche) ---
$autoload_paths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php', 
    dirname(__DIR__) . '/vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    realpath(__DIR__ . '/../') . '/vendor/autoload.php'
];

$autoload_found = false;
foreach ($autoload_paths as $path) {
    if (file_exists($path)) {
        require $path;
        $autoload_found = true;
        log_message('Autoload gefunden: ' . basename(dirname($path)));
        break;
    }
}

if (!$autoload_found) {
    log_message('FEHLER: PHPMailer Autoload nicht gefunden');
    send_response(false, 'Serverfehler: E-Mail-System nicht verfügbar', 500);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- Environment Variables laden (erweiterte Pfadsuche) ---
$env_paths = [
    __DIR__ . '/.env',
    __DIR__ . '/../.env',
    dirname(__DIR__) . '/.env',
    __DIR__ . '/../../.env',
    realpath(__DIR__ . '/../') . '/.env'
];

$env_loaded = false;
foreach ($env_paths as $env_path) {
    if (file_exists($env_path)) {
        $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, ' "\'');
            if (!empty($key) && !empty($value)) {
                $_ENV[$key] = $value;
            }
        }
        log_message('.env geladen');
        $env_loaded = true;
        break;
    }
}

if (!$env_loaded) {
    log_message('WARNUNG: .env nicht gefunden');
}

// --- Konfiguration ---
$config = [
    'recipient' => 'info@ingos-kleine-kaeltehilfe-saarbruecken.de',
    'from_email' => 'info@ingos-kleine-kaeltehilfe-saarbruecken.de',
    'from_name' => 'Ingos kleine Kältehilfe Website',
    'smtp_host' => $_ENV['SMTP_HOST'] ?? 'smtp.strato.de',
    'smtp_port' => (int)($_ENV['SMTP_PORT'] ?? 587),
    'smtp_user' => $_ENV['SMTP_USER'] ?? 'info@ingos-kleine-kaeltehilfe-saarbruecken.de',
    'smtp_pass' => $_ENV['SMTP_PASS'] ?? '',
    'debug' => filter_var($_ENV['MAIL_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)
];

// Validierung der kritischen Konfiguration
if (empty($config['smtp_pass'])) {
    log_message('FEHLER: SMTP_PASS nicht konfiguriert');
    send_response(false, 'Serverfehler: E-Mail-Konfiguration unvollständig', 500);
}

// --- Request-Validierung ---
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    log_message('Ungültige HTTP-Methode: ' . ($_SERVER['REQUEST_METHOD'] ?? 'none'));
    send_response(false, 'Nur POST-Requests erlaubt', 405);
}

// CSRF-Protection (einfache Implementierung)
$allowedOrigins = [
    'https://ingos-kleine-kaeltehilfe-saarbruecken.de',
    'http://localhost',
    'http://127.0.0.1'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
$originValid = false;
foreach ($allowedOrigins as $allowed) {
    if (strpos($origin, $allowed) === 0) {
        $originValid = true;
        break;
    }
}

if (!$originValid && !empty($origin)) {
    log_message('CSRF: Unerlaubte Origin: ' . $origin);
    send_response(false, 'Anfrage von unerlaubter Quelle', 403);
}

// --- Eingabe-Bereinigung ---
function clean($value) {
    return trim(htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

// --- Formular-Verarbeitung ---
log_message('Formular-Anfrage empfangen');

$subject = '';
$body = '';
$replyTo = '';
$formType = 'unbekannt';

// 1. Newsletter-Anmeldung
if (!empty($_POST['subscribe-email'])) {
    $email = clean($_POST['subscribe-email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        log_message('Newsletter: Ungültige E-Mail-Adresse');
        send_response(false, 'Ungültige E-Mail-Adresse', 400);
    }
    
    $formType = 'Newsletter';
    $subject = 'Newsletter-Anmeldung von Website';
    $body = "Newsletter-Anmeldung\n\n";
    $body .= "E-Mail: $email\n";
    $body .= "Anmeldedatum: " . date('d.m.Y H:i:s') . "\n";
    $body .= "IP-Adresse: " . $clientIP . "\n";
    $replyTo = $email;

// 2. Ehrenamt-Formular
} elseif (!empty($_POST['volunteer-name']) && !empty($_POST['volunteer-email'])) {
    $name = clean($_POST['volunteer-name']);
    $email = clean($_POST['volunteer-email']);
    $volunteerSubject = clean($_POST['volunteer-subject'] ?? 'Ehrenamtliche Anfrage');
    $message = clean($_POST['volunteer-message'] ?? '');
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        send_response(false, 'Ungültige E-Mail-Adresse', 400);
    }
    
    $formType = 'Ehrenamt';
    $subject = "Ehrenamtliche Anfrage: $volunteerSubject";
    $body = "Ehrenamtliche Anfrage\n\n";
    $body .= "Name: $name\n";
    $body .= "E-Mail: $email\n";
    $body .= "Betreff: $volunteerSubject\n";
    if ($message) $body .= "Nachricht:\n$message\n";
    $body .= "\nEingang: " . date('d.m.Y H:i:s') . "\n";
    $replyTo = $email;

// 3. Kontakt-Formular
} elseif (!empty($_POST['first-name']) && !empty($_POST['email'])) {
    $name = clean($_POST['first-name']);
    $email = clean($_POST['email']);
    $message = clean($_POST['message'] ?? '');
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        send_response(false, 'Ungültige E-Mail-Adresse', 400);
    }
    
    $formType = 'Kontakt';
    $subject = 'Kontaktanfrage von Website';
    $body = "Kontaktanfrage\n\n";
    $body .= "Name: $name\n";
    $body .= "E-Mail: $email\n\n";
    $body .= "Nachricht:\n$message\n";
    $body .= "\nEingang: " . date('d.m.Y H:i:s') . "\n";
    $replyTo = $email;

} else {
    log_message('Unvollständige Formulardaten');
    send_response(false, 'Unvollständige Formulardaten', 400);
}

log_message("Formular-Typ: $formType verarbeitet");

// --- E-Mail-Versand ---
$mail = new PHPMailer(true);

try {
    // SMTP-Konfiguration
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_user'];
    $mail->Password = $config['smtp_pass'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['smtp_port'];
    $mail->SMTPDebug = $config['debug'] ? 2 : 0;
    $mail->CharSet = 'UTF-8';

    // Absender/Empfänger
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['recipient']);
    
    if (!empty($replyTo)) {
        $mail->addReplyTo($replyTo);
    }

    // E-Mail-Inhalt
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;

    // Versenden
    $mail->send();
    
    log_message("$formType-E-Mail erfolgreich versendet an " . $config['recipient']);
    send_response(true, ucfirst($formType) . '-Anfrage erfolgreich gesendet');

} catch (Exception $e) {
    log_message("FEHLER beim Versand: " . $mail->ErrorInfo);
    send_response(false, 'E-Mail konnte nicht versendet werden', 500);
}
?>
