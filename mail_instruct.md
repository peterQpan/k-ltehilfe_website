# Hosting-Anleitung für den Mailversand

Diese Webseite wird mit Hugo erzeugt und benötigt einige Schritte, damit der Mailversand auf dem Strato-Webspace funktioniert.

1. **E-Mail-Postfach einrichten**
   - Im Strato-Kundenbereich ein Postfach `info@ingos-kleine-kältehilfe.de` anlegen. 
   - Notiere das Passwort – es wird für spätere Tests benötigt.

2. **Projekt bauen**
   - Stelle sicher, dass Hugo installiert ist (`hugo version`).
   - Führe im Projektverzeichnis `hugo --minify` aus. Der fertige Inhalt landet im Ordner `public/`.
   - Die Datei `static/mail.php` wird dabei automatisch in das Root-Verzeichnis der fertigen Seite kopiert.

3. **Dateien hochladen**
   - Verbinde dich per FTP/SFTP mit dem Strato-Webspace.
   - Lade den Inhalt des Ordners `public/` in das Stammverzeichnis der Domain.
   - Stelle sicher, dass `mail.php`, `instagram.php` und `image_proxy.php` mit Leserechten (z. B. 644) vorliegen.

4. **Mailversand testen**
   - Öffne die Website im Browser und fülle die Formulare unter Kontakt, Mithelfen oder Newsletter aus.
   - Eine Testmail sollte an `info@ingos-kleine-kältehilfe.de` ankommen.
   - Falls keine E-Mail ankommt, prüfe zunächst den Spam-Ordner.

5. **Fehlerbehebung**
   - Strato blockiert teilweise den Versand; warte bei neu eingerichteten Postfächern bis zu 4 Wochen auf volle Freischaltung.
   - Falls `mail()` nicht zuverlässig funktioniert, integriere eine SMTP-Lösung wie [PHPMailer](https://github.com/PHPMailer/PHPMailer) und
     nutze den Server `smtp.strato.de` mit Port `587` und den Zugangsdaten des oben angelegten Postfachs.
   - Bei anhaltenden Problemen die Logdateien im Strato-Kundenbereich prüfen oder den Support kontaktieren.

6. **Sicherheit**
   - Sensible Passwörter nicht im Repository hinterlegen.
   - Regelmäßig Updates für Hugo und PHP auf dem Server einspielen.

Nach diesen Schritten sollte der Mailversand der Formulare funktionieren.
