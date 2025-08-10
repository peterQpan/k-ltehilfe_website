# Pflege und Deployment der Webseite

## Lokale Entwicklung
1. Wechsle in den Ordner `charity`.
2. Starte einen lokalen Server, z. B. mit Python:
   ```bash
   python3 -m http.server 8000
   ```
3. Öffne `http://localhost:8000` im Browser, um die Seite zu testen.

## Hosting
- Die Seite besteht aus statischen Dateien und kann auf jedem Webserver oder über Dienste wie GitHub Pages bereitgestellt werden.
- Lade den Inhalt des `charity`-Ordners auf den Server.

## Farben und Schriften
- Primärfarbe: `#1ca1a0`
- Sekundärfarbe: `#0788cc`
- Akzentfarbe: `#feae01`
- Die Schrift **Open Sans** wird über Google Fonts eingebunden. Anpassungen erfolgen in `css/templatemo-kind-heart-charity.css`.

## Logo und Bilder
- Das Vereinslogo befindet sich in `../kh2000.jpeg` und wird in den Templates per relativem Pfad eingebunden.
- Zum Austausch anderer Bilder die Datei ersetzen oder einen externen Bildlink verwenden. Beim Commit keine neuen Binärdateien hinzufügen.

## Texte aktualisieren
- Inhalte befinden sich direkt in den HTML-Dateien (`index.html`, `donate.html`, `news.html`, `news-detail.html`).
- Öffne die entsprechende Datei und passe Texte an. Für neue Beiträge kann ein Block aus `news.html` kopiert und mit eigenen Daten gefüllt werden.

## Neue Beiträge hinzufügen
1. Kopiere einen bestehenden `news-block` in `news.html` und passe Bild, Überschrift, Datum und Text an.
2. Für ausführliche Artikel dupliziere `news-detail.html` und verlinke ihn aus `news.html`.

## Soziale Medien
- Links zu Facebook und Instagram befinden sich in den Header- und Footer-Bereichen der HTML-Dateien.
- Passe die URLs bei Bedarf direkt dort an.

## Kontaktinformationen
- Adresse, Telefon und E-Mail sind an mehreren Stellen in den HTML-Dateien eingetragen. Bei Änderungen alle Vorkommen aktualisieren.

## Hinweise
- Bei Änderungen immer einen lokalen Testlauf durchführen.
- Keine binären Dateien (Bilder, Fonts) in Commits aufnehmen.
