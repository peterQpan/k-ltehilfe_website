# Hinweise zum Hosting und zur E-Mail-Funktion

Damit die Website mit funktionierendem Kontaktversand bei **Strato** läuft, sind folgende Schritte notwendig:

1. **E-Mail-Postfach einrichten**
   - Stelle sicher, dass das Postfach `info@ingos-kleine-kältehilfe.de` im Strato-Kundenbereich existiert.
   - Neue Postfächer sollten idealerweise älter als 4 Wochen sein, da Strato sonst den Versand einschränkt.

2. **`mail.php` bereitstellen**
   - Die Datei befindet sich im Ordner `static/`. Beim Ausführen von `hugo` wird sie in das Ausgabeverzeichnis (`public/`) kopiert.
   - Lade nach dem Build sowohl die Inhalte von `public/` als auch die `mail.php` Datei auf den Webspace hoch.
   - Die Datei muss im Document‑Root (z. B. `/`) liegen, damit die Formulare sie unter `/mail.php` erreichen.

3. **Versand testen**
   - Auf dem Server muss PHP aktiviert sein.
   - Nach dem Upload eine Testanfrage über jedes Formular auslösen und prüfen, ob eine E-Mail bei `info@ingos-kleine-kältehilfe.de` eingeht.
   - Falls keine E-Mail ankommt, Server‑Logs prüfen und sicherstellen, dass `mail()` bei Strato erlaubt ist. Eventuell das `-f` Flag im Skript anpassen.

4. **Optional: Wechsel zu PHPMailer**
   - Für höhere Zuverlässigkeit kann statt der `mail()`‑Funktion [PHPMailer](https://github.com/PHPMailer/PHPMailer) über `smtp.strato.de` verwendet werden.
   - Installation: `composer require phpmailer/phpmailer` (oder manuell hochladen).
   - SMTP-Daten: Benutzername und Passwort des Postfachs, Port 587 (STARTTLS) oder 465 (SSL).
   - Im Skript `mail.php` die PHPMailer-Konfiguration ergänzen und die Zugangsdaten über Umgebungsvariablen oder eine `.env` Datei setzen.

5. **Seite mit Hugo bauen und deployen**
   - Lokal: `hugo` ausführen, dadurch entsteht der Ordner `public/`.
   - Den gesamten Inhalt von `public/` auf den Strato-Webspace laden.
   - Wiederhole den Schritt nach jeder Änderung am Quellcode.

6. **Zugriffsrechte**
   - Auf Strato sollten PHP-Dateien in der Regel mit den Rechten `644` funktionieren. Bei Problemen die Berechtigungen prüfen.

Nach Durchführung dieser Schritte sollten die Formulare auf der Website funktionsfähig sein und E-Mails an `info@ingos-kleine-kältehilfe.de` versenden.
