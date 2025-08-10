# Pflegeanleitung

Diese Anleitung erklärt, wie die Webseite von **Ingos kleiner Kältehilfe** verwaltet wird.

## Hosting
1. Lade den gesamten Ordner `template` gemeinsam mit dem Ordner `images` und `fonts` auf einen beliebigen Webspace oder GitHub Pages hoch.
2. Stelle sicher, dass sich die Datei `index.html` im Wurzelverzeichnis deiner Hosting-Umgebung befindet.
3. Für eine lokale Vorschau kann im Projektordner folgender Befehl verwendet werden:
   ```bash
   python3 -m http.server
   ```
   Anschließend ist die Seite unter `http://localhost:8000/template/index.html` erreichbar.

## Bilder austauschen
- Grafiken liegen im Ordner `template/images`.
- Ersetze eine Grafik durch eine neue Datei mit gleichem Namen oder passe in den HTML-Dateien den `src`-Pfad an.
- Das Vereinslogo befindet sich im Projektwurzelverzeichnis als `kältehilfe2000x2000.jpeg`. Bei einem neuen Logo muss nur diese Datei ersetzt werden.

## Texte und Farben anpassen
- Die wichtigsten Texte stehen in den Dateien `index.html`, `donate.html`, `news.html` und `news-detail.html`.
- Farbwerte werden in `css/templatemo-kind-heart-charity.css` über CSS-Variablen definiert. Hier können weitere Vereinsfarben eingetragen werden.

## Beiträge (News) ändern
1. `news.html` listet alle Beiträge auf. Füge einen neuen Block nach dem vorhandenen Schema hinzu.
2. Für detaillierte Artikel kopiere `news-detail.html` und passe Inhalt sowie Dateiname an. Verlinke den neuen Artikel in `news.html`.

## Spendenformular
- Die Seite `donate.html` enthält ein einfaches Formular. Um echte Zahlungen zu verarbeiten, muss es an einen Zahlungsdienst wie PayPal oder ein Bankformular angebunden werden.

## Social Media
- Links zu Facebook und Instagram werden im Kopf- und Fußbereich der HTML-Dateien gepflegt. Adressen einfach austauschen, falls sich die Profile ändern.

Viel Erfolg bei der weiteren Pflege der Webseite!
