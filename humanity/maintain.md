# Wartung und Deployment

Diese Anleitung beschreibt, wie die Website der **Ingos kleinen Kältehilfe – Hand in Hand e.V.** gepflegt und veröffentlicht wird.

## Hosting
1. Stelle sicher, dass ein Webserver (z. B. Apache, Nginx oder ein statischer Hostingdienst wie GitHub Pages) verwendet wird.
2. Lade den Inhalt des Ordners `humanity` auf den Server oder in das entsprechende Verzeichnis des Hostingdienstes hoch.
3. Achte darauf, dass die Datei `kh2000.jpeg` im Projektwurzelverzeichnis liegt und vom Server erreichbar ist.
4. Prüfe die Seite im Browser, indem du die Domain oder `index.html` aufrufst.

## Bilder austauschen
- Ersetze vorhandene Bilddateien im Ordner `humanity/img` oder `humanity/images` durch neue Dateien gleichen Namens.
- Alternativ kannst du in den HTML-Dateien die `src`‑Attribute der `<img>`‑Tags auf neue Dateipfade oder externe Bild-URLs ändern.
- Logos werden aus `../kh2000.jpeg` geladen. Wenn ein neues Logo verwendet wird, ersetze diese Datei im Projektwurzelverzeichnis.

## Beiträge ändern und hinzufügen
- Blogeinträge auf der Startseite befinden sich im Abschnitt `<section class="blog">` in `index.html`.
- Jeder Beitrag besteht aus einem `<div class="item col-md-4">` mit Überschrift (`<h4>`), Text (`<p>`) und einer Schaltfläche (`<div class="button-main bg-fio-point">`).
- Um neue Beiträge hinzuzufügen, kopiere einen vorhandenen `item`‑Block und passe Inhalte sowie Bilder an.

## Farben anpassen
- Die Vereinsfarben sind in `humanity/css/styles.css` als Hilfsklassen (`.col-red`, `.col-green`, `.bg-blue-point`, `.bg-fio-point`, `.bg-green`) definiert.
- Passe diese Klassen bei Bedarf an, um neue Farbtöne zu verwenden.

## Schriftarten
- Die Website nutzt die Google-Font **Open Sans**. Änderungen können im `<head>` der HTML-Dateien vorgenommen werden.

## Soziale Medien
- Die Links zu Facebook und Instagram befinden sich im Footer der HTML-Dateien. Aktualisiere die `href`-Attribute bei Änderungen der URLs.

## Entwicklung
- Für lokale Tests kann im Projektverzeichnis ein einfacher Server gestartet werden:
  ```bash
  python3 -m http.server 8000
  ```
  Rufe dann `http://localhost:8000/humanity/index.html` im Browser auf.
- Nach Änderungen sollten HTML und CSS auf Syntaxfehler geprüft werden.
