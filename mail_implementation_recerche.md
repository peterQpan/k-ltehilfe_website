### E-Mails via PHP-Skript auf Strato Hosting versenden: Ausführliche Anleitung

Strato Hosting ist ein beliebter deutscher Provider, der strenge Regeln für den E-Mail-Versand aus Skripten hat, um Spam zu verhindern. Der Versand von E-Mails über externe SMTP-Server (außerhalb von Strato) ist nicht möglich, da dies als Sicherheitsrisiko gilt und zu einer Sperrung des Accounts führen kann. Stattdessen muss der offizielle Strato-SMTP-Server `smtp.strato.de` verwendet werden, mit obligatorischer Authentifizierung. Neue E-Mail-Postfächer (jünger als 4 Wochen) unterliegen möglicherweise einem temporären SPAM-Schutz, der den Versand bei zu vielen Mails einschränkt.

Es gibt zwei gängige Methoden, E-Mails via PHP zu versenden: Die einfache, aber potenziell eingeschränkte `mail()`-Funktion und die empfohlene Variante mit einer Bibliothek wie PHPMailer über SMTP. Die `mail()`-Funktion nutzt den lokalen Mail-Transfer-Agent (MTA) des Servers und könnte auf Strato funktionieren, wird aber zunehmend als unsicher betrachtet und kann blockiert werden (z. B. bei Massenversand). PHPMailer ist sicherer, flexibler und unterstützt Anhänge, HTML-Mails und bessere Fehlerbehandlung. Im Folgenden erkläre ich beide Methoden schrittweise, inklusive Code-Beispielen, Konfigurationen und Tipps. Alle Beispiele basieren auf PHP 7+ und Strato-spezifischen Einstellungen.

#### Voraussetzungen
- **Hosting-Paket:** Du brauchst ein Strato-Hosting-Paket mit PHP-Unterstützung (z. B. Webhosting oder Server-Pakete). PHP muss aktiviert sein (standardmäßig in den meisten Paketen).
- **E-Mail-Postfach:** Erstelle ein dediziertes Postfach bei Strato (z. B. `mailer@deine-domain.de`), um Sicherheitsrisiken zu minimieren. Verwende dessen Zugangsdaten für die Authentifizierung. Das Passwort kannst du im Strato-Kundenlogin ändern oder zurücksetzen.
- **SMTP-Details für Strato:**
  - Server: `smtp.strato.de`
  - Ports: 465 (für SSL/TLS) oder 587 (für STARTTLS, empfohlen für Skripte).
  - Verschlüsselung: SSL/TLS (Versionen 1.2 oder 1.3).
  - Authentifizierung: Mit deiner vollständigen E-Mail-Adresse (z. B. `deine-email@domain.de`) und dem Passwort. Methode: "Normales Passwort" (kein CRAM-MD5).
- **Einschränkungen:** Strato erlaubt keinen offenen Mail-Relay (ohne Auth). Es gibt Sende-Limits (z. B. 100–500 Mails pro Stunde, abhängig vom Paket – prüfe im Kundenbereich). Bei Verletzungen droht eine Sperre. Vermeide Massenversand; für Newsletters nutze dedizierte Dienste.
- **Testumgebung:** Teste Skripte lokal oder auf einem Staging-Server, bevor du sie live stellst. Aktiviere Fehlerausgaben in PHP mit `error_reporting(E_ALL); ini_set('display_errors', 1);`.

#### Methode 1: E-Mails mit der PHP `mail()`-Funktion versenden
Die `mail()`-Funktion ist in PHP integriert und erfordert keine zusätzlichen Bibliotheken. Auf Strato funktioniert sie oft, da sie den lokalen MTA nutzt, der intern an `smtp.strato.de` weiterleitet. Allerdings ist sie unsicher (keine Auth, anfällig für Injection-Attacken) und kann bei Strato blockiert werden, wenn der Header nicht korrekt ist.

**Schritt-für-Schritt-Anleitung:**
1. Erstelle eine PHP-Datei (z. B. `send_mail.php`) in deinem Strato-Webspace (via FTP oder Dateimanager).
2. Setze den Absender-Header auf eine domain-eigene E-Mail-Adresse (z. B. `@deine-domain.de`), um Spam-Filter zu umgehen.
3. Führe das Skript aus (z. B. über einen Browser-Aufruf oder Cron-Job).

**Code-Beispiel (einfache Text-Mail):**
```php
<?php
// Fehleranzeige aktivieren (für Debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Empfänger, Betreff und Nachricht
$to = 'empfaenger@example.com';
$subject = 'Test-E-Mail von Strato';
$message = 'Hallo, dies ist eine Testnachricht via PHP mail().';

// Header für Absender (muss domain-eigen sein, um bei Strato durchzugehen)
$headers = 'From: mailer@deine-domain.de' . "\r\n" .
           'Reply-To: mailer@deine-domain.de' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

// Versand versuchen
if (mail($to, $subject, $message, $headers)) {
    echo 'E-Mail erfolgreich versendet!';
} else {
    echo 'Fehler beim Versand. Überprüfe Server-Logs.';
}
?>
```

**Tipps und Einschränkungen:**
- Füge `-f mailer@deine-domain.de` als fünften Parameter hinzu, wenn Probleme auftreten: `mail($to, $subject, $message, $headers, '-f mailer@deine-domain.de');`.
- Für HTML-Mails: Setze `Content-type: text/html; charset=utf-8` in den Headers.
- Häufige Fehler: Wenn es nicht funktioniert, könnte Strato `mail()` für dein Paket deaktiviert haben – wechsle zu PHPMailer.
- Nicht für Massenversand geeignet, da jede Mail eine neue Socket-Verbindung öffnet.

#### Methode 2: E-Mails mit PHPMailer via SMTP versenden (empfohlen)
PHPMailer ist eine Open-Source-Bibliothek, die SMTP-Auth unterstützt und auf Strato zuverlässig funktioniert. Sie ist sicherer als `mail()` und erlaubt erweiterte Features wie Anhänge oder HTML.

**Schritt-für-Schritt-Anleitung:**
1. **Installation:**
   - **Via Composer (empfohlen, wenn Composer auf Strato verfügbar):** Führe im Projektverzeichnis `composer require phpmailer/phpmailer` aus (via SSH, falls aktiviert). Binde es ein mit `require 'vendor/autoload.php';`.
   - **Manuell:** Lade PHPMailer von GitHub herunter (https://github.com/PHPMailer/PHPMailer), entpacke es in ein Verzeichnis (z. B. `/PHPMailer`) und binde die Dateien ein:
     ```php
     use PHPMailer\PHPMailer\PHPMailer;
     use PHPMailer\PHPMailer\SMTP;
     use PHPMailer\PHPMailer\Exception;
     require 'PHPMailer/src/Exception.php';
     require 'PHPMailer/src/PHPMailer.php';
     require 'PHPMailer/src/SMTP.php';
     ```

2. Erstelle eine PHP-Datei und konfiguriere SMTP.
3. Teste das Skript.

**Code-Beispiel (HTML-Mail mit Debugging):**
```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer einbinden (anpassen je nach Installation)
require 'vendor/autoload.php'; // Oder manuelle Pfade

// Fehleranzeige aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mail = new PHPMailer(true); // true aktiviert Exceptions

try {
    // SMTP-Konfiguration für Strato
    $mail->isSMTP();
    $mail->SMTPDebug = 2; // Debug-Level: 0=aus, 1=Client, 2=Client+Server
    $mail->Host = 'smtp.strato.de';
    $mail->SMTPAuth = true;
    $mail->Username = 'mailer@deine-domain.de'; // Deine Strato-E-Mail
    $mail->Password = 'DeinPasswort'; // Passwort
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Oder ENCRYPTION_SMTPS für Port 465
    $mail->Port = 587; // 587 für STARTTLS, 465 für SSL

    // Absender und Empfänger
    $mail->setFrom('mailer@deine-domain.de', 'Dein Name');
    $mail->addAddress('empfaenger@example.com', 'Empfänger Name');
    $mail->addCC('kopie@example.com'); // Optional

    // Inhalt
    $mail->isHTML(true);
    $mail->Subject = 'Test-E-Mail via PHPMailer auf Strato';
    $mail->Body = '<h1>Hallo!</h1><p>Dies ist eine <strong>HTML-Testnachricht</strong>.</p>';
    $mail->AltBody = 'Hallo! Dies ist eine Text-Testnachricht.'; // Für Text-Clients

    // Optional: Anhang
    // $mail->addAttachment('/pfad/zur/datei.pdf');

    $mail->send();
    echo 'E-Mail erfolgreich versendet!';
} catch (Exception $e) {
    echo "Fehler beim Versand: {$mail->ErrorInfo}";
}
?>
```

**Tipps und häufige Fehler:**
- **Debugging:** Setze `SMTPDebug = 2;` für detaillierte Logs. Häufige Fehler: "SMTP connect() failed" (falscher Port/Secure) oder "Authentication failed" (falsches Passwort – setze es zurück).
- **Port-Auswahl:** Verwende 587 mit STARTTLS für bessere Kompatibilität; 465 mit SSL, wenn STARTTLS scheitert.
- **Sicherheit:** Speichere Passwörter nicht hartcodiert – nutze Umgebungsvariablen oder .env-Dateien.
- **Kontaktformulare:** Integriere PHPMailer in Formulare, um User-Eingaben zu sanitieren (z. B. mit `filter_var()`), um Injection zu vermeiden.
- Wenn es nicht funktioniert: Überprüfe Strato-Logs im Kundenbereich oder kontaktiere den Support. Ein separates Postfach hilft oft.

#### Weitere Hinweise und Best Practices
- **Limits und Spam-Vermeidung:** Sende nicht mehr als 100 Mails pro Stunde, um Sperren zu vermeiden. Für Newsletters wechsle zu Diensten wie Mailchimp.
- **Alternativen:** Wenn PHPMailer zu komplex ist, probiere SwiftMailer oder integrierte WordPress-Plugins wie WP Mail SMTP (falls du WordPress nutzt).
- **Testen:** Sende Test-Mails an dich selbst und prüfe den Spam-Ordner.
- **Aktualisierungen:** Halte PHP und Bibliotheken aktuell, um Sicherheitslücken zu schließen.

Falls du spezifische Fehler hast oder Code anpassen möchtest, gib mehr Details!