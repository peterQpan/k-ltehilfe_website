# Wartung und Anpassung der Webseite

Diese Anleitung erklärt, wie die Seite "Ingos kleine Kältehilfe" angepasst und betrieben werden kann.

## Lokales Testen und Hosting

1. Stelle sicher, dass ein aktueller Browser installiert ist.
2. Navigiere im Terminal in den Ordner `humanity` und starte einen einfachen Webserver:
   ```bash
   python3 -m http.server 8000
   ```
3. Öffne [http://localhost:8000](http://localhost:8000) im Browser.
4. Für den Produktivbetrieb kann jeder statische Hoster (z. B. GitHub Pages, Netlify) verwendet werden. Lade dazu den Inhalt des Ordners `humanity` hoch.

## Bilder austauschen

- Das Logo liegt eine Ebene höher als der Ordner `humanity` und wird in den HTML-Dateien als `../kh2000.jpeg` eingebunden.
- Eigene Bilder können in `humanity/img/` abgelegt oder von externen Quellen geladen werden.
- Achte darauf, keine neuen Binärdateien in Pull Requests aufzunehmen. Nutze stattdessen bestehende Bilder oder externe Links.

## Farben und Schriftarten

- Primärfarbe: `#e50510`
- Sekundärfarbe: `#1ca1a0`
- Akzentfarbe: `#feae01`
- Weitere Anpassungen können in `css/styles.css` vorgenommen werden.

## Inhalte anpassen

### Navigation
Die Navigationspunkte befinden sich in jeder HTML-Datei innerhalb des `<nav>`-Blocks. Text oder Reihenfolge können dort geändert werden.

### Startseitige Beiträge
- In `index.html` gibt es drei Beispielbeiträge im Abschnitt `Neuigkeiten`.
- Jeder Beitrag besteht aus einem `<div class="item col-md-4">` Block.
- Um einen Beitrag zu ändern, passe Überschrift (`<h4>`), Text (`<p>`) und gegebenenfalls das Bild (`<img src="img/...">`) an.
- Für neue Beiträge kopiere einen vorhandenen Block und füge ihn innerhalb von `<section class="blog">` ein.

### Team- und Angebotsseiten
- Texte zu Angeboten stehen in `service.html`.
- Team-Mitglieder werden in `team.html` definiert. Bildquellen und Namen lassen sich dort bearbeiten.

## Social Media Links
- Die Links zu Facebook und Instagram befinden sich im Footer jeder Seite innerhalb des Elements `.social-share`.
- Passe die URLs an, falls sich die Profile ändern.

## Deployment
- Prüfe vor dem Hochladen alle Änderungen im Browser.
- Läuft die Seite auf einem externen Server, lade nur die HTML-, CSS- und JS-Dateien hoch. Bilder sollten separat gehostet oder per CDN eingebunden werden.

Viel Erfolg bei der Weiterentwicklung!
