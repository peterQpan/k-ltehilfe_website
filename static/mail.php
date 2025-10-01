<?php
// Server-Mail-Endpunkt (für /mail.php auf dem Server)
// Diese Datei ist NICHT Teil des Hugo-builds und darf im Server-Deploy unter /mail.php liegen.

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');

$isJsonRequest = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
function send_response_s(bool $ok, string $msg, int $code = 200): void
{
    global $isJsonRequest;
    http_response_code($ok ? 200 : $code);
    header('Content-Type: ' . ($isJsonRequest ? 'application/json' : 'text/plain') . '; charset=utf-8');
    if ($isJsonRequest) echo $ok ? json_encode(['success' => $msg]) : json_encode(['error' => $msg]);
    else echo $msg;
    exit;
}

function get_logs_dir(): string
{
    $d = realpath(__DIR__ . '/../logs') ?: (__DIR__ . '/../logs');
    if (!is_dir($d)) @mkdir($d, 0700, true);
    return $d;
}

function write_log_s(string $m): void
{
    $f = rtrim(get_logs_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'mail.log';
    @file_put_contents($f, date('Y-m-d H:i:s') . ' ' . ($_SERVER['REMOTE_ADDR'] ?? '') . ' ' . $m . PHP_EOL, FILE_APPEND | LOCK_EX);
}

// fixed .env path
function load_env_server(): array
{
    $f = __DIR__ . '/../config/.env';
    $env = [];
    if (!is_readable($f)) return $env;
    foreach (file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim(trim($v), "'\"");
    }
    return $env;
}

$env = load_env_server();
write_log_s('env loaded: ' . (empty($env) ? 'no' : 'yes'));
write_log_s('DEBUG_MODE: ' . ($env['DEBUG_MODE'] ?? 'not set'));
write_log_s('RATE_LIMIT_MINUTES: ' . ($env['RATE_LIMIT_MINUTES'] ?? 'not set'));
write_log_s('SMTP_PORT: ' . ($env['SMTP_PORT'] ?? 'not set'));
write_log_s('SMTP_USERNAME set: ' . (!empty($env['SMTP_USERNAME']) ? 'yes' : 'no'));
write_log_s('SMTP_PASSWORD set: ' . (!empty($env['SMTP_PASSWORD']) ? 'yes' : 'no'));

// fixed autoload location under public/vendor
$autoload = __DIR__ . '/../public/vendor/autoload.php';
if (!file_exists($autoload)) {
    write_log_s('ERROR: autoload not found at ' . $autoload);
    send_response_s(false, 'Serverfehler: PHPMailer nicht verfügbar', 500);
}
require $autoload;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = [
    'smtp_host' => $env['SMTP_HOST'] ?? '',
    'smtp_port' => (int)($env['SMTP_PORT'] ?? 587),
    'smtp_user' => $env['SMTP_USERNAME'] ?? $env['SMTP_USER'] ?? '',
    'smtp_pass' => $env['SMTP_PASSWORD'] ?? $env['SMTP_PASS'] ?? '',
    'from_email' => $env['SMTP_FROM_EMAIL'] ?? $env['FROM_EMAIL'] ?? '',
    'from_name' => $env['SMTP_FROM_NAME'] ?? $env['FROM_NAME'] ?? 'Website',
    'recipient' => $env['CONTACT_EMAIL'] ?? $env['TO_EMAIL'] ?? '',
    'allowed_origin' => $env['ALLOWED_ORIGINS'] ?? '',
    'rate_minutes' => (int)($env['RATE_LIMIT_MINUTES'] ?? 5),
    'rate_max' => (int)($env['RATE_LIMIT_MAX_ATTEMPTS'] ?? 3),
];

if (empty($config['smtp_host']) || empty($config['smtp_user']) || empty($config['smtp_pass']) || empty($config['recipient'])) {
    write_log_s('ERROR: incomplete config');
    send_response_s(false, 'Serverfehler: E-Mail-Konfiguration unvollständig', 500);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { write_log_s('METHOD not allowed'); send_response_s(false, 'Nur POST erlaubt', 405); }

// rate limiting
function check_rate_limit_server(string $ip, int $minutes, int $max): bool
{
    $dir = get_logs_dir();
    $file = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'ratelimit_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $ip) . '.json';
    $now = time();
    $window = $minutes * 60;
    $attempts = [];
    if (is_readable($file)) {
        $data = @file_get_contents($file);
        $attempts = json_decode($data, true) ?: [];
        $attempts = array_filter($attempts, function ($t) use ($now, $window) { return ($now - (int)$t) <= $window; });
    }
    if (count($attempts) >= $max) return false;
    $attempts[] = $now;
    @file_put_contents($file, json_encode($attempts), LOCK_EX);
    return true;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!check_rate_limit_server($ip, $config['rate_minutes'], $config['rate_max'])) { write_log_s('RATE_LIMIT ' . $ip); send_response_s(false, 'Zu viele Anfragen', 429); }

function clean_server(string $v, int $max = 2000): string { return mb_substr(trim(strip_tags($v)), 0, $max); }

$subject = '';
$body = '';
$replyTo = '';

if (!empty($_POST['volunteer-name']) || !empty($_POST['volunteer-email'])) {
    $name = clean_server($_POST['volunteer-name'] ?? '');
    $email = clean_server($_POST['volunteer-email'] ?? '');
    $subject = clean_server($_POST['volunteer-subject'] ?? 'Mithelfen');
    $body = "Mithelfen-Anfrage\n\n";
    $body .= "Name: $name\nE-Mail: $email\nBetreff: $subject\n";
    $replyTo = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
} elseif (!empty($_POST['first-name']) || !empty($_POST['message'])) {
    $name = clean_server($_POST['first-name'] ?? '');
    $email = clean_server($_POST['email'] ?? '');
    $message = clean_server($_POST['message'] ?? '');
    $subject = 'Kontaktanfrage';
    $body = "Kontaktanfrage\n\n";
    $body .= "Name: $name\nE-Mail: $email\n\nNachricht:\n$message\n";
    $replyTo = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
} elseif (!empty($_POST['subscribe-email'])) {
    $email = clean_server($_POST['subscribe-email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { write_log_s('Invalid subscribe ' . $email); send_response_s(false, 'Ungültige E-Mail', 400); }
    $subject = 'Newsletter-Anmeldung';
    $body = "Newsletter\n\nE-Mail: $email\n";
    $replyTo = $email;
} else { write_log_s('Incomplete POST: ' . json_encode(array_keys($_POST))); send_response_s(false, 'Unvollständige Daten', 400); }

$body .= "\nIP: $ip\nReferer: " . ($_SERVER['HTTP_REFERER'] ?? '') . "\n";

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2; // Detaillierte Debug-Ausgabe
    $mail->Debugoutput = function($str, $level) {
        write_log_s("SMTP DEBUG [$level]: $str");
    };
    $mail->isSMTP();
    $mail->Host = gethostbyname($config['smtp_host']); // IPv4 erzwingen
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_user'];
    $mail->Password = $config['smtp_pass'];
    $secure = strtolower($env['SMTP_SECURE'] ?? 'ssl');
    if ($secure === 'ssl') $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; else $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int)$config['smtp_port'];
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['recipient']);
    if ($replyTo !== '') $mail->addReplyTo($replyTo);
    $mail->isHTML(false);
    $mail->Subject = mb_substr($subject, 0, 200);
    $mail->Body = $body;
    $mail->send();
    write_log_s('Sent to ' . $config['recipient'] . ' subj:' . $subject);
    send_response_s(true, 'OK', 200);
} catch (Exception $e) {
    write_log_s('PHPMailer error: ' . $mail->ErrorInfo . ' ex:' . $e->getMessage());
    send_response_s(false, 'E-Mail konnte nicht gesendet werden', 500);
}
