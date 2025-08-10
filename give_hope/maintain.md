# Pflege und Hosting der Webseite

Diese Anleitung erklärt, wie du die angepasste Vorlage *Ingos kleine Kältehilfe – Hand in Hand e.V.* verwaltest.

## Hosting
1. Kopiere den Inhalt des Ordners `give_hope` auf einen Webserver (z.B. Apache, Nginx oder GitHub Pages).
2. Stelle sicher, dass die Datei `kh2000.jpeg` als Logo erreichbar ist. Lege sie z.B. im Stammverzeichnis des Servers ab und passe bei Bedarf den Pfad im Tag `<img src="../kh2000.jpeg">` an.
3. Starte den Server und rufe `index.html` im Browser auf.

## Bilder austauschen
- Alle Platzhalterbilder befinden sich im Verzeichnis `images`.
- Ersetze die Dateien durch neue Bilder mit gleichem Dateinamen oder passe die Pfade in den HTML-Dateien an.
- Achte darauf, keine binären Dateien ins Repository zu committen. Bilder sollten nur auf dem Server abgelegt werden.

## Beiträge ändern oder hinzufügen
- Blogübersichten findest du in `blog.html`, einzelne Beiträge in `blog-single.html`.
- Zum Ändern eines Beitrags bearbeite die entsprechenden HTML-Dateien (Titel, Text, Datum, Bildpfad).
- Für einen neuen Beitrag kopiere `blog-single.html`, passe den Inhalt an und verlinke ihn in `blog.html`.

## Farben und Stil
- Zentrale Farben sind in `css/custom.css` definiert.
- Passe dort Variablen `--primary`, `--accent` und `--highlight` an, wenn sich das Farbschema ändert.

## Soziale Medien
- Links zu Facebook und Instagram befinden sich im Footer jeder Seite.
- Aktualisiere die URLs bei Änderungen direkt in den HTML-Dateien.

## Kontaktinformationen
- Adresse, Telefon und E-Mail sind im Footer verankert.
- Bei Änderungen diese Werte in allen HTML-Seiten aktualisieren (Suche nach `Stengelstr. 30` erleichtert dies).

## Lokale Vorschau
- Zum Testen kannst du im Projektverzeichnis einen simplen Server starten:
  ```bash
  python -m http.server 8000
  ```
  Öffne dann `http://localhost:8000/index.html` im Browser.

Viel Erfolg beim Pflegen der Webseite!
