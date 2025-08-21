<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "webmaster@ingos-kleine-kältehilfe.de"; // <--- Empfänger geändert
    $subject = "Formular-Eingang";
    $message = "Neue Nachricht vom Webformular:\n\n";
    $headers = "From: info@ingos-kleine-kältehilfe.de";


    // Kontaktformular
    if (isset($_POST['first-name'])) {
        $message .= "Vorname: " . ($_POST['first-name'] ?? '') . "\n";
        $message .= "E-Mail: " . ($_POST['email'] ?? '') . "\n";
        $message .= "Nachricht: " . ($_POST['message'] ?? '') . "\n";
    }
    // Mithelfen-Formular
    elseif (isset($_POST['volunteer-name'])) {
    $message .= "Name: " . ($_POST['volunteer-name'] ?? '') . "\n";
    $message .= "E-Mail: " . ($_POST['volunteer-email'] ?? '') . "\n";
    $message .= "Betreff: " . ($_POST['volunteer-subject'] ?? '') . "\n";
    }
    // Newsletter-Formular
    elseif (isset($_POST['subscribe-email'])) {
        $message .= "Newsletter-Anmeldung: " . ($_POST['subscribe-email'] ?? '') . "\n";
    }
    // Fallback für unbekannte Felder
    else {
        foreach ($_POST as $key => $value) {
            $message .= ucfirst($key) . ": " . $value . "\n";
        }
    }

    // Server-zu-Server TLS-Verschlüsselung über SMTP
    $smtpConfig = [
        'host' => 'smtp.ingos-kleine-kältehilfe.de', // Dein SMTP-Server
        'port' => 587, // STARTTLS Port (oder 465 für SSL)
        'encryption' => 'tls', // oder 'ssl'
        'username' => 'info@ingos-kleine-kältehilfe.de',
        'password' => '', // SMTP-Passwort hier eintragen
    ];
    
    // Erweiterte Headers für bessere Zustellung
    $headers = "From: info@ingos-kleine-kältehilfe.de\r\n";
    $headers .= "Reply-To: info@ingos-kleine-kältehilfe.de\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Einfache AES-Verschlüsselung als Fallback (optional)
    $encryptionKey = 'IhreGeheimer32ZeichenKey12345678'; // 32 Zeichen für AES-256
    if (function_exists('openssl_encrypt')) {
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($message, 'AES-256-CBC', $encryptionKey, 0, $iv);
        if ($encrypted !== false) {
            $message = "=== VERSCHLÜSSELTE NACHRICHT ===\n" . base64_encode($iv . $encrypted);
            $subject .= ' (AES verschlüsselt)';
        }
    }
    
    mail($to, $subject, $message, $headers);
    echo '<div style="margin:2em auto;max-width:500px;padding:1em;border:2px solid #4caf50;background:#e8f5e9;color:#333;text-align:center;font-size:1.2em;">'
        . 'Vielen Dank für deine Nachricht!<br>Wir melden uns so schnell wie möglich.'
        . '</div>';
    exit();
}
?>