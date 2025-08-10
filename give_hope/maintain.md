# Pflegeanleitung

## Lokales Hosting
1. Im Projektordner `k-ltehilfe_website` ausführen:
   ```
   python -m http.server 8000
   ```
2. Im Browser `http://localhost:8000/k-hilfe/` öffnen.

## Bilder austauschen
- Das Logo befindet sich als `kh2000.jpeg` im Projektstamm. Ersetze die Datei durch ein gleich benanntes Bild, um das Logo zu ändern.
- Weitere Bilder werden aus dem Ordner `give_hope/images` geladen. Tausche dort Dateien mit gleichem Namen aus, um Inhalte zu ersetzen.

## Beiträge aktualisieren
- Textliche Inhalte findest du in `k-hilfe/index.html`, `k-hilfe/impressum.html` und `k-hilfe/datenschutz.html`.
- Bearbeite die HTML-Dateien direkt, um Abschnitte wie „Wer wir sind“ oder „So kannst du helfen“ anzupassen.

## Neue Beiträge hinzufügen
- Lege neue HTML-Dateien im Ordner `k-hilfe` an.
- Ergänze das Navigationsmenü in `index.html`, indem du ein weiteres `<li>`-Element mit einem Link zur neuen Seite einfügst.

## Deployment
- Lade den gesamten Ordner `k-ltehilfe_website` auf einen Webserver oder nutze Hosting-Dienste wie GitHub Pages.
- Achte darauf, relative Pfade beizubehalten, damit die Seite unabhängig vom Host funktioniert.
