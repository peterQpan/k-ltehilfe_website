# Implementierung: Mail-Versand auf Strato mit PHPMailer

Diese Anleitung beschreibt, wie das Kontaktformular-Skript `static/mail.php` auf Strato korrekt eingerichtet und betrieben wird. Ziel ist der zuverlässige Versand der Formulareingaben über den Strato-SMTP-Server statt der deaktivierten PHP-`mail()`-Funktion.

## Übersicht
- Versand via PHPMailer über `smtp.strato.de` (Port 587, STARTTLS)
- Absender und Empfänger: `info@ingos-kleine-kaeltehilfe.de`
- Zugangsdaten werden NICHT im Code gespeichert, sondern via Umgebungsvariablen bereitgestellt

## 1) Lokale Vorbereitung (optional)
Falls du lokal testen möchtest:
1. PHP 7.4+ und Composer bereitstellen.
2. Im Projektordner einmal Abhängigkeiten installieren:
   ```bash
   composer require phpmailer/phpmailer
   ```
3. Lokale Test-Variablen exportieren (nur für den Test, niemals committen):
   ```bash
   export SMTP_HOST=smtp.strato.de
   export SMTP_PORT=587
   export SMTP_USER="info@ingos-kleine-kaeltehilfe.de"
   export SMTP_PASS="<STRATO_POSTFACH_PASSWORT>"
   export MAIL_DEBUG=1   # optional
   ```

## 2) Dateien auf Strato bereitstellen
Ziel ist, dass `mail.php` und der `vendor/`-Ordner gemeinsam auf dem Webspace liegen.

Option A – Composer lokal, Upload per SFTP/Dateimanager:
- Lokal `composer install` ausführen (siehe oben).
- Folgende Artefakte deployen (z. B. in das Webroot-Verzeichnis, in dem die Webseite liegt – häufig `httpdocs/` oder `htdocs/`):
  - `static/mail.php` (ggf. nach `mail.php` im Zielverzeichnis benennen bzw. Pfad anpassen)
  - `vendor/`-Ordner (inkl. `vendor/autoload.php`)

Option B – Composer per SSH auf Strato (wenn SSH verfügbar):
- Projekt via Git auf Strato bereitstellen oder hochladen.
- Im Projektverzeichnis ausführen:
  ```bash
  composer install --no-dev --prefer-dist --optimize-autoloader
  ```

Wichtig: `mail.php` erwartet `vendor/autoload.php` entweder im selben Verzeichnis oder eine Ebene darüber. Lege daher `mail.php` und `vendor/` am besten ins gleiche Verzeichnis.

## 3) Umgebungsvariablen im Strato-Backend setzen
Strato bietet keine klassischen System-Umgebungsvariablen für PHP-FPM, aber du kannst Werte in `.htaccess` setzen oder per `ini_set()`/`putenv()` vorbereiten. Empfohlene Wege:

Variante A – `.htaccess` im selben Ordner wie `mail.php` anlegen/erweitern:
```apache
# .htaccess
SetEnv SMTP_HOST smtp.strato.de
SetEnv SMTP_PORT 587
SetEnv SMTP_USER info@ingos-kleine-kaeltehilfe.de
SetEnv SMTP_PASS <STRATO_POSTFACH_PASSWORT>
# optionales Debugging (0/1)
SetEnv MAIL_DEBUG 0
```
Hinweis: Falls `SetEnv` serverseitig nicht erlaubt ist, nutze Variante B.

Variante B – kleine Bootstrap-Datei (z. B. `config/env.php`) und diese am Anfang von `mail.php` einbinden. Inhalt:
```php
<?php
putenv('SMTP_HOST=smtp.strato.de');
putenv('SMTP_PORT=587');
putenv('SMTP_USER=info@ingos-kleine-kaeltehilfe.de');
putenv('SMTP_PASS=<STRATO_POSTFACH_PASSWORT>');
putenv('MAIL_DEBUG=0');
```
Dann in `mail.php` direkt nach `header(...)` ergänzen:
```php
require __DIR__ . '/config/env.php';
```
Achte darauf, `config/env.php` NICHT in die Versionskontrolle zu committen.

## 4) Formular-Aktionen anpassen
Stelle sicher, dass deine Formulare (z. B. in `layouts/...`) das `action`-Attribut auf die bereitgestellte `mail.php` zeigen und per POST senden, z. B.:
```html
<form action="/mail.php" method="post">
  <!-- Felder wie first-name, email, message usw. -->
</form>
```
Falls `mail.php` in einem Unterordner liegt, den Pfad entsprechend anpassen.

## 5) SPF/DMARC/DKIM prüfen
Damit Mails nicht im Spam landen:
- SPF: In den DNS-Einträgen deiner Domain sicherstellen, dass Strato als erlaubter Sender eingetragen ist (typisch über Strato-Vorlagen im Kundenbereich).
- DMARC/DKIM: Falls verfügbar, aktivieren/konfigurieren. Strato-Dokumentation beachten.

## 6) Test und Fehlersuche
- Sende eine Test-Nachricht über das Formular.
- Bei Fehlern:
  - Prüfe, ob `vendor/autoload.php` gefunden wird (ansonsten 500er-Hinweis). Ordnerstruktur korrigieren.
  - Setze temporär `MAIL_DEBUG=1`, um SMTP-Logs zu erhalten (Logausgabe erscheint als Response-Inhalt oder im Error-Log, je nach Setup).
  - Zugangsdaten (Benutzer/Passwort) und Port prüfen: 587 + STARTTLS.
  - Absender (`From`) muss zu deiner Domain gehören: `info@ingos-kleine-kaeltehilfe.de`.

## 7) Sicherheit
- Niemals SMTP-Passwörter committen.
- Zugriffe auf `mail.php` sind nur POST erlaubt (bereits umgesetzt). Rate-Limiting/CSRF kannst du optional ergänzen.

## 8) Wartung
- Bibliotheken gelegentlich aktualisieren:
  ```bash
  composer update phpmailer/phpmailer --with-all-dependencies
  ```
- Nach Updates testen.

---
Kurzcheck: Was muss vorhanden sein?
- [ ] `mail.php` und `vendor/` im gleichen Ordner
- [ ] SMTP_* Variablen gesetzt (z. B. via `.htaccess` SetEnv oder `config/env.php`)
- [ ] Formular `action` verweist korrekt auf `mail.php`
- [ ] SPF/DMARC im DNS geprüft

Fertig – das Formular sendet nun zuverlässig über Strato SMTP.