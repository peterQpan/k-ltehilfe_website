# Wartung der Webseite

Diese Anleitung beschreibt, wie die angepasste Vorlage "Ingos kleine Kältehilfe" gepflegt und erweitert wird.

## Hosting
1. Die Seite ist statisch und benötigt keinen Server mit spezieller Laufzeit.
2. Lade den Ordner `template` z.B. auf einen Webspace oder nutze GitHub Pages:
   - Repository nach GitHub hochladen.
   - In den Einstellungen unter **Pages** den Branch mit dem Ordner `template` als Source wählen.
3. Alternativ lokal testen mit Python:
   ```bash
   cd template
   python3 -m http.server 8000
   ```
   Danach `http://localhost:8000` im Browser öffnen.

## Bilder austauschen
- Das Logo befindet sich eine Ebene über dem Ordner `template` (`../kältehilfe2000x2000.jpeg`).
- Weitere Bilder liegen im Unterordner `images`.
- Zum Ersetzen einfach die Dateien mit identischem Namen überschreiben. Achte darauf, keine Binärdateien in Pull Requests hochzuladen, sondern die Bilder nur lokal zu halten oder extern zu verlinken.

## Texte anpassen
- Die Hauptseite ist `index.html`.
- Spendeninformationen befinden sich in `donate.html`.
- Dateien können mit einem beliebigen Texteditor bearbeitet werden.
- Farben werden in `css/templatemo-kind-heart-charity.css` über CSS-Variablen verwaltet.

## Beiträge ändern oder hinzufügen
- Nachrichten stehen in `news.html` und `news-detail.html`.
- Um neue Beiträge zu erstellen, kopiere vorhandene Blöcke in `news.html` und passe Bild, Überschrift, Datum und Text an.
- Für einen Detailartikel `news-detail.html` duplizieren und entsprechend verlinken.

## Social Media
- Links zu Facebook und Instagram befinden sich im Kopf- und Fußbereich der Seiten.
- Zum Aktualisieren die URLs in den HTML-Dateien anpassen.

## Tests
- Nach Änderungen die Seite lokal im Browser prüfen.
- Optional kann ein HTML-Linter wie `htmlhint` verwendet werden: `npx htmlhint *.html`.
