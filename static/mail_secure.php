<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// .env Datei laden (außerhalb Web-Root!)
require_once 'env_loader.php';
try {
    // Pfad zur .env Datei außerhalb des Web-Roots
    $envPath = dirname(__DIR__) . '/.env'; // Geht eine Ebene hoch, dann .env
    loadEnv($envPath);
} catch (Exception $e) {
    die("Fehler beim Laden der Umgebungsvariablen: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Umgebungsvariablen laden
        $smtpHost = getEnvironmentVar('SMTP_HOST');
        $smtpPort = getEnvironmentVar('SMTP_PORT');
        $smtpEncryption = getEnvironmentVar('SMTP_ENCRYPTION');
        $smtpUsername = getEnvironmentVar('SMTP_USERNAME');
        $smtpPassword = getEnvironmentVar('SMTP_PASSWORD');
        $mailTo = getEnvironmentVar('MAIL_TO');
        
        $subject = "Formular-Eingang";
        $message = "Neue Nachricht vom Webformular:\n\n";
        
        // Formulardaten verarbeiten
        if (isset($_POST['first-name'])) {
            $message .= "Vorname: " . ($_POST['first-name'] ?? '') . "\n";
            $message .= "E-Mail: " . ($_POST['email'] ?? '') . "\n";
            $message .= "Nachricht: " . ($_POST['message'] ?? '') . "\n";
        }
        elseif (isset($_POST['volunteer-name'])) {
            $message .= "Name: " . ($_POST['volunteer-name'] ?? '') . "\n";
            $message .= "E-Mail: " . ($_POST['volunteer-email'] ?? '') . "\n";
            $message .= "Betreff: " . ($_POST['volunteer-subject'] ?? '') . "\n";
        }
        elseif (isset($_POST['subscribe-email'])) {
            $message .= "Newsletter-Anmeldung: " . ($_POST['subscribe-email'] ?? '') . "\n";
        }
        else {
            foreach ($_POST as $key => $value) {
                $message .= ucfirst($key) . ": " . $value . "\n";
            }
        }
        
        // SMTP-basierter Versand mit fsockopen (ohne externe Bibliotheken)
        $success = sendSmtpMail($smtpHost, $smtpPort, $smtpEncryption, $smtpUsername, $smtpPassword, $mailTo, $subject, $message);
        
        if ($success) {
            echo '<div style="margin:2em auto;max-width:500px;padding:1em;border:2px solid #4caf50;background:#e8f5e9;color:#333;text-align:center;font-size:1.2em;">'
                . 'Vielen Dank für deine Nachricht!<br>Wir melden uns so schnell wie möglich.'
                . '</div>';
        } else {
            throw new Exception("E-Mail konnte nicht gesendet werden");
        }
        
    } catch (Exception $e) {
        echo '<div style="margin:2em auto;max-width:500px;padding:1em;border:2px solid #f44336;background:#ffebee;color:#333;text-align:center;font-size:1.2em;">'
            . 'Entschuldigung, beim Senden der E-Mail ist ein Fehler aufgetreten.<br>'
            . 'Fehler: ' . htmlspecialchars($e->getMessage())
            . '</div>';
    }
    exit();
}

/**
 * E-Mail über SMTP senden (ohne externe Bibliotheken)
 */
function sendSmtpMail($host, $port, $encryption, $username, $password, $to, $subject, $message) {
    // Socket erstellen
    if ($encryption === 'ssl') {
        $socket = fsockopen("ssl://$host", $port, $errno, $errstr, 30);
    } else {
        $socket = fsockopen($host, $port, $errno, $errstr, 30);
    }
    
    if (!$socket) {
        throw new Exception("Verbindung zu SMTP-Server fehlgeschlagen: $errstr ($errno)");
    }
    
    // SMTP-Dialog
    $response = fgets($socket, 256);
    if (substr($response, 0, 3) !== '220') {
        throw new Exception("SMTP-Server Antwort erwartet 220, erhalten: $response");
    }
    
    // EHLO
    fputs($socket, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n");
    $response = fgets($socket, 256);
    
    // STARTTLS für TLS-Verschlüsselung
    if ($encryption === 'tls') {
        fputs($socket, "STARTTLS\r\n");
        $response = fgets($socket, 256);
        if (substr($response, 0, 3) !== '220') {
            throw new Exception("STARTTLS fehlgeschlagen: $response");
        }
        
        // Socket zu TLS upgraden
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        
        // Erneut EHLO nach STARTTLS
        fputs($socket, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n");
        $response = fgets($socket, 256);
    }
    
    // Authentifizierung
    fputs($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 256);
    if (substr($response, 0, 3) !== '334') {
        throw new Exception("AUTH LOGIN fehlgeschlagen: $response");
    }
    
    fputs($socket, base64_encode($username) . "\r\n");
    $response = fgets($socket, 256);
    if (substr($response, 0, 3) !== '334') {
        throw new Exception("Username-Authentifizierung fehlgeschlagen: $response");
    }
    
    fputs($socket, base64_encode($password) . "\r\n");
    $response = fgets($socket, 256);
    if (substr($response, 0, 3) !== '235') {
        throw new Exception("Passwort-Authentifizierung fehlgeschlagen: $response");
    }
    
    // E-Mail senden
    fputs($socket, "MAIL FROM: <$username>\r\n");
    $response = fgets($socket, 256);
    
    fputs($socket, "RCPT TO: <$to>\r\n");
    $response = fgets($socket, 256);
    
    fputs($socket, "DATA\r\n");
    $response = fgets($socket, 256);
    
    // E-Mail-Headers und -Body
    $emailData = "From: $username\r\n";
    $emailData .= "To: $to\r\n";
    $emailData .= "Subject: $subject\r\n";
    $emailData .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $emailData .= "Date: " . date('r') . "\r\n";
    $emailData .= "\r\n";
    $emailData .= $message;
    $emailData .= "\r\n.\r\n";
    
    fputs($socket, $emailData);
    $response = fgets($socket, 256);
    
    // Verbindung beenden
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    return substr($response, 0, 3) === '250';
}
?>
