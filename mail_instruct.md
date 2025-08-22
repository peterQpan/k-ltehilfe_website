# Anleitung zur Inbetriebnahme des Mailversands

Das Skript `static/mail.php` verarbeitet die Formularanfragen der Website und sendet sie an `info@ingos-kleine-kaeltehilfe.de`. Damit der Versand auf dem Strato-Hosting funktioniert, sind folgende Schritte nötig:

1. **E-Mail-Postfach anlegen**
   - Lege im Strato-Kundenmenü ein Postfach `info@ingos-kleine-kaeltehilfe.de` an.
   - Warte ggf. bis das Postfach mindestens vier Wochen alt ist, damit der SPAM-Schutz den Versand nicht blockiert.

2. **Seite bauen**
   - Erzeuge die statische Ausgabe mit Hugo:
     ```bash
     hugo
     ```
   - Im Ausgabeverzeichnis (`public/`) liegt danach automatisch auch `mail.php`.

3. **Dateien hochladen**
   - Übertrage den Inhalt des `public/`-Ordners auf den Webspace von Strato (z. B. per FTP oder Dateimanager).
   - Stelle sicher, dass `mail.php` im Dokumentenstamm (Root) liegt.

4. **PHP-Konfiguration prüfen**
   - Aktiviere in der Strato-Verwaltung PHP 8 oder höher.
   - Setze Dateirechte für `mail.php` auf `644` (Lesen+Schreiben für Besitzer, Lesen für andere).

5. **Versand testen**
   - Teste alle drei Formulare der Website:
     - „Mithelfen“ (`volunteer-form`)
     - „Kontakt“ (`contact-form`)
     - „Newsletter“ (`subscribe-form`)
   - Prüfe, ob Mails im Postfach ankommen (ggf. auch im Spam-Ordner).

6. **Fehlerbehebung & Erweiterung**
   - Bei Versandproblemen bietet Strato Logs im Kundenbereich.
   - Für umfangreichere Anforderungen (HTML-Mails, Anhänge, bessere Fehlerbehandlung) kann PHPMailer mit dem Strato-SMTP-Server (`smtp.strato.de`, Port 587) eingesetzt werden; siehe `mail_implementation_recerche.md`.

Nach erfolgreichem Upload und Test sollte der Mailversand für alle Formulare funktionsfähig sein.
