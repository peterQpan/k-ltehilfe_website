# Wartung und Deployment der Webseite

## Hosting
1. Stelle sicher, dass alle Dateien im Ordner `charity` auf einen Webserver hochgeladen werden.
2. Für einen lokalen Test kann im Projektordner folgender Befehl genutzt werden:
   ```bash
   python3 -m http.server 8080
   ```
   Anschließend ist die Seite unter <http://localhost:8080/charity/> erreichbar.

## Logo und Bilder
- Das Vereinslogo befindet sich als `kältehilfe2000x2000.jpeg` im Projektstamm. In den HTML-Dateien wird es relativ eingebunden (`../kältehilfe2000x2000.jpeg`).
- Weitere Bilder werden über externe Quellen eingebunden. Um eigene Bilder zu verwenden, ersetze einfach die entsprechenden URLs in den HTML-Dateien.

## Farben und Schriftarten
- Farben können zentral in `css/templatemo-kind-heart-charity.css` über die CSS‑Variablen angepasst werden.
- Die Schriftart stammt von Google Fonts (`Roboto`). Andere Fonts können durch Anpassung des Links im `<head>` der HTML-Dateien eingebunden werden.

## Beiträge ändern und hinzufügen
- Die Seite `news.html` listet aktuelle Beiträge. Jeder Beitrag besteht aus einem Block mit Bild, Titel und Text.
- Neue Beiträge können hinzugefügt werden, indem ein bestehender Block kopiert und der Inhalt angepasst wird. Für Detailseiten kann `news-detail.html` dupliziert und der Inhalt angepasst werden.

## Kontakt- und Spendeninformationen
- Adresse, Telefon und E‑Mail sind in allen Seiten im Kopf- und Fußbereich hinterlegt. Ändern Sie diese bei Bedarf konsistent in allen Dateien.
- Spendenbeträge und Texte auf `donate.html` lassen sich direkt in den Formularfeldern anpassen.

Viel Erfolg beim Einsatz der Seite! Bei Fragen einfach melden.
