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

function normalize_origin_s(string $value): string
{
    $parts = parse_url(trim($value));
    if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
        return '';
    }

    $origin = strtolower($parts['scheme']) . '://' . strtolower($parts['host']);
    if (!empty($parts['port'])) {
        $origin .= ':' . (int)$parts['port'];
    }

    return $origin;
}

function get_default_origin_s(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = trim($_SERVER['HTTP_HOST'] ?? '');
    if ($host === '') {
        return '';
    }

    return normalize_origin_s($scheme . '://' . $host);
}

function get_allowed_origins_s(string $configuredOrigins): array
{
    $origins = [];
    foreach (explode(',', $configuredOrigins) as $origin) {
        $normalized = normalize_origin_s($origin);
        if ($normalized !== '') {
            $origins[$normalized] = true;
        }
    }

    $defaultOrigin = get_default_origin_s();
    if ($defaultOrigin !== '') {
        $origins[$defaultOrigin] = true;
    }

    return array_keys($origins);
}

function get_request_origin_s(): string
{
    $origin = normalize_origin_s($_SERVER['HTTP_ORIGIN'] ?? '');
    if ($origin !== '') {
        return $origin;
    }

    return normalize_origin_s($_SERVER['HTTP_REFERER'] ?? '');
}

function resolve_autoload_s(): string
{
    $candidates = [
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/../public/vendor/autoload.php',
    ];

    foreach ($candidates as $candidate) {
        if (is_readable($candidate)) {
            return $candidate;
        }
    }

    return '';
}

function is_valid_name_s(string $value): bool
{
    if ($value === '' || mb_strlen($value) < 2 || mb_strlen($value) > 80) {
        return false;
    }

    if (!preg_match("/^[\p{L}][\p{L}\p{M}\s.'-]+$/u", $value)) {
        return false;
    }

    return preg_match_all('/\p{L}/u', $value) >= 2;
}

function is_valid_form_age_s(string $value, int $minimumAgeSeconds = 3, int $maximumAgeSeconds = 86400): bool
{
    if ($value === '' || !ctype_digit($value)) {
        return false;
    }

    $submittedAt = (int)$value;
    $now = time();
    $age = $now - $submittedAt;

    return $age >= $minimumAgeSeconds && $age <= $maximumAgeSeconds;
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

// Resolve Composer autoload robustly for both source and deployed locations.
$autoload = resolve_autoload_s();
if ($autoload === '') {
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

$allowedOrigins = get_allowed_origins_s($config['allowed_origin']);
$requestOrigin = get_request_origin_s();
if ($requestOrigin === '' || !in_array($requestOrigin, $allowedOrigins, true)) {
    write_log_s('ORIGIN denied: ' . ($requestOrigin !== '' ? $requestOrigin : 'missing'));
    send_response_s(false, 'Anfrage nicht erlaubt', 403);
}

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
    $honeypot = clean_server($_POST['volunteer-website'] ?? '', 255);
    $loadedAt = clean_server($_POST['volunteer-form-loaded-at'] ?? '', 32);
    if ($honeypot !== '') { write_log_s('Volunteer honeypot hit'); send_response_s(false, 'Ungültige Anfrage', 400); }
    if (!is_valid_form_age_s($loadedAt)) { write_log_s('Volunteer form age invalid'); send_response_s(false, 'Ungültige Anfrage', 400); }
    if (!is_valid_name_s($name)) { write_log_s('Volunteer name invalid'); send_response_s(false, 'Bitte gib einen gültigen Namen an', 400); }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { write_log_s('Volunteer email invalid'); send_response_s(false, 'Bitte gib eine gültige E-Mail-Adresse an', 400); }
    $subject = 'Ich möchte mithelfen!';
    $body = "Mithelfen-Anfrage\n\n";
    $body .= "Name: $name\nE-Mail: $email\nBetreff: $subject\n";
    $replyTo = $email;
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
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
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
