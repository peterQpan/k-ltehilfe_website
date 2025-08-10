# Wartung und Anpassung der Website

## Hosting

- Stelle sicher, dass [Node.js](https://nodejs.org/) oder ein einfacher Webserver verfügbar ist.
- Zum lokalen Testen im Projektordner ausführen:
  ```
  python -m http.server 8000
  ```
  Danach ist die Seite unter http://localhost:8000/k-hilfe/ erreichbar.
- Für produktives Hosting können alle Dateien aus dem Ordner `k-hilfe` auf einen beliebigen Webspace hochgeladen werden.

## Bilder austauschen

- Das Logo wird aus der Datei `kh2000.jpeg` im Projektstamm geladen. Ersetze diese Datei durch ein anderes Bild gleichen Namens oder passe den Pfad in den HTML-Dateien an.
- Weitere Bilder können über externe URLs eingebunden werden, um binäre Dateien im Repository zu vermeiden.

## Beiträge ändern und hinzufügen

- Texte auf der Startseite befinden sich in `k-hilfe/index.html`.
- Aktualisiere Kontakt- oder Spendeninformationen direkt im entsprechenden Abschnitt.
- Für neue Hinweise oder Blogeinträge können zusätzliche Abschnitte oder Unterseiten im Ordner `k-hilfe` erstellt werden.

## Farben und Schriftarten

- Hauptfarben werden in `k-hilfe/styles.css` definiert (`--primary`, `--accent`, `--secondary`).
- Schriftarten werden über Google Fonts eingebunden und können in der ersten Zeile der CSS-Datei angepasst werden.
