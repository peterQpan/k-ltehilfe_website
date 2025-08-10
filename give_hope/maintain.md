# Wartungsanleitung für die Website

Diese Anleitung beschreibt, wie die Website der Initiative **Ingos kleine Kältehilfe – Hand in Hand e.V.** gepflegt wird.

## Hosting
1. Öffne ein Terminal und wechsle in das Verzeichnis `k-hilfe`.
2. Starte einen einfachen Webserver:
   ```bash
   python -m http.server 8000
   ```
3. Rufe die Seite im Browser unter [http://localhost:8000](http://localhost:8000) auf.
4. Für ein dauerhaftes Hosting können beliebige statische Webhoster (z.B. GitHub Pages, Netlify) genutzt werden. Lade dazu den Inhalt von `k-hilfe` hoch.

## Bilder austauschen
- Das Vereinslogo liegt als `kh2000.jpeg` im Projektwurzelverzeichnis. Ersetze es durch eine Datei gleichen Namens, um das Logo zu aktualisieren.
- Weitere Bilder können online verlinkt werden oder in einen Unterordner gelegt werden. Achte darauf, die Pfade in den HTML-Dateien anzupassen.

## Beiträge ändern und hinzufügen
- Texte der Startseite befinden sich in `k-hilfe/index.html`.
- Impressum und Datenschutzerklärung sind in `k-hilfe/impressum.html` bzw. `k-hilfe/datenschutz.html` zu finden.
- Bearbeite die Dateien mit einem Texteditor deiner Wahl und speichere die Änderungen.

## Farben und Schriften
- Primärfarben werden in `k-hilfe/style.css` über CSS-Variablen definiert. Passe die Hex-Werte dort an.
- Die Schrift wird über Google Fonts eingebunden. Um eine andere Schrift zu nutzen, ändere den Link im `<head>` der HTML-Dateien.

## Social-Media-Links
- Facebook und Instagram Links befinden sich im Abschnitt "Folge uns" in `index.html`. Aktualisiere die URLs bei Bedarf.

## Tests
- Nach Änderungen sollten folgende Befehle ausgeführt werden:
  ```bash
  npx --yes html-validate k-hilfe/index.html
  npx --yes html-validate k-hilfe/impressum.html k-hilfe/datenschutz.html
  npx --yes htmlhint k-hilfe/index.html k-hilfe/impressum.html k-hilfe/datenschutz.html
  ```
- Zum lokalen Testen kann zusätzlich ein Server gestartet werden:
  ```bash
  python -m http.server 8000
  ```
