# Wartung und Aktualisierung

Diese Anleitung hilft dir dabei, die angepasste Webseite **Ingos kleine Kältehilfe** zu pflegen und zu veröffentlichen.

## Hosting
1. Kopiere den Ordner `charity` auf einen Webserver oder nutze einen statischen Hoster (z. B. GitHub Pages, Netlify).
2. Stelle sicher, dass sich das Vereinslogo `kh2000.jpeg` eine Ebene über dem Ordner befindet. Alternativ kann der Pfad der Logo‑Datei in den HTML-Dateien angepasst werden.
3. Öffne `index.html` im Browser, um die Seite zu testen.

## Farben und Schrift
- Die Hauptfarben sind in `css/templatemo-kind-heart-charity.css` unter `:root` definiert.
- Schriften werden über Google Fonts geladen und müssen nicht lokal installiert werden.

## Bilder austauschen
1. Ersetze bestehende Bilder im Verzeichnis `images/` durch eigene Dateien mit demselben Namen **oder** passe die Bildpfade in den HTML-Dateien an.
2. Achte darauf, keine Binärdateien in Commit-Meldungen aufzunehmen, wenn du Änderungen einreichst.

## Beiträge bearbeiten
- `news.html` listet aktuelle Beiträge auf.
- `news-detail.html` enthält eine Beispielseite für einen einzelnen Beitrag.
- Um neue Beiträge hinzuzufügen, kopiere einen vorhandenen Block in `news.html` und passe Titel, Datum, Text und Bild an.
- Für ausführliche Beiträge erstelle eine neue Datei auf Basis von `news-detail.html` und verlinke sie in `news.html`.

## Spenden- und Kontaktinformationen
- E‑Mail, Adresse und Telefonnummer können zentral in den HTML-Dateien aktualisiert werden (`hand-in-hand@gmx.de`, `Stengelstr. 30, 66117 Saarbrücken`, `+49 174 9117841`).
- Social‑Media‑Links sind im Kopf- und Fußbereich eingetragen.

## Lokale Vorschau
1. Starte einen einfachen Webserver, z. B. mit Python:
   ```bash
   cd charity
   python3 -m http.server 8000
   ```
2. Rufe im Browser `http://localhost:8000` auf.

## Tipps
- Bewahre Struktur und Farbschema, um ein konsistentes Erscheinungsbild zu gewährleisten.
- Teste nach jeder Änderung die Navigation und alle Links.
