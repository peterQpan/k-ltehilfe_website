# Wartung und Hosting

Diese Anleitung beschreibt, wie die angepasste Webseite "Ingos kleine Kältehilfe" gepflegt und veröffentlicht wird.

## Hosting
1. Lade den gesamten Ordner `charity` auf einen Webserver oder nutze einen Dienst wie GitHub Pages, Netlify oder Vercel.
2. Stelle sicher, dass alle Dateien in derselben Verzeichnisstruktur liegen. Der Logo‑Pfad verweist auf `../kh2000.jpeg`; das Bild muss sich im Projektwurzelverzeichnis befinden.
3. Aktiviere HTTPS und konfiguriere die Domain nach Bedarf.

## Inhalte anpassen
### Logo und Bilder
- Ersetze `kh2000.jpeg` im Projektwurzelverzeichnis durch ein neues Logo mit gleichem Dateinamen oder passe den Pfad in den HTML-Dateien an.
- Weitere Bilder liegen im Unterordner `images`. Da keine Binärdateien committet werden, sollten neue Bilder extern gehostet oder bereits vorhandene ersetzt werden.

### Farben und Schriftarten
- Farbcodes werden in `css/templatemo-kind-heart-charity.css` definiert. Passe die Variablen unter `:root` an, um neue Farben zu verwenden.
- Schriftarten werden über Google Fonts eingebunden. Bei Bedarf kann der `<link>` im `<head>` der HTML-Dateien angepasst werden.

### Beiträge (Aktuelles)
- Die Seite `news.html` listet Beiträge auf. Kopiere einen bestehenden Block `<div class="news-block">` und passe Bild, Datum und Text an.
- Für Detailseiten dupliziere `news-detail.html`, passe den Inhalt an und verlinke ihn in `news.html`.

### Kontakt- und Sozialdaten
- Aktualisiere Adresse, Telefon oder E-Mail in den Header-, Kontakt- und Fußzeilenblöcken der HTML-Dateien.
- Facebook- und Instagram-Links befinden sich ebenfalls in Header und Footer. Ersetze sie bei Änderungen der Social-Media-Präsenzen.

## Deployment prüfen
- Öffne `index.html` lokal im Browser, um die Funktionalität zu prüfen.
- Teste Links zu Social-Media-Seiten, Formularen und Anfahrt, um sicherzustellen, dass sie korrekt funktionieren.
- Nach jeder Änderung sollten die HTML-Dateien validiert werden (z.B. mit [W3C Validator](https://validator.w3.org/)).

