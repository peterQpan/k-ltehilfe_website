# Anleitung für den E-Mail-Versand auf Strato

Diese Website nutzt das Script `mail.php` (im `static`-Verzeichnis), um Formulare an
`info@ingos-kleine-kältehilfe.de` weiterzuleiten. Damit der Versand auf dem Strato-Server funktioniert,
sind folgende Schritte notwendig:

1. **E-Mail-Postfach vorbereiten**
   - Lege im Strato-Kundenbereich ein Postfach an (z. B. `info@ingos-kleine-kältehilfe.de`).
   - Notiere Benutzername und Passwort; sie werden für SMTP benötigt.

2. **Zugangsdaten als Umgebungsvariablen setzen**
   - Auf dem Server müssen die Variablen `SMTP_USER` und `SMTP_PASS` verfügbar sein.
   - Alternativ können die Werte direkt in `static/mail.php` eingetragen werden, dies ist
     jedoch weniger sicher.

3. **Dateien hochladen**
   - Baue die Website mit `hugo` und lade den erzeugten `public`-Ordner auf den Strato-Webspace.
   - Stelle sicher, dass die Dateien `mail.php` und der Ordner `vendor` im Webroot liegen,
     damit PHPMailer geladen werden kann.

4. **PHP-Version prüfen**
   - `mail.php` benötigt PHP 7.4 oder höher. Die Strato-Konsole erlaubt die Auswahl der PHP-Version.

5. **Versand testen**
   - Rufe die Website auf und sende eine Testnachricht über jedes Formular.
   - Prüfe das Postfach sowie ggf. den Spam-Ordner.

6. **Fehleranalyse**
   - Bei Problemen aktivere temporär `SMTPDebug` in `mail.php` (Wert 1 oder 2), um detaillierte
     Logs zu erhalten.

> Hinweise: Strato erlaubt pro Stunde nur eine begrenzte Anzahl an Mails. Für größere
Newsletter bitte einen spezialisierten Dienst nutzen.
