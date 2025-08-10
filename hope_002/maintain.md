# Pflege der Webseite

Diese Anleitung beschreibt, wie die angepasste Version des Templates betrieben und aktualisiert wird.

## Hosting
1. Das Verzeichnis `give_hope` auf einen Webserver oder Dienst wie GitHub Pages bzw. Netlify hochladen.
2. Die `index.html` ist die Startseite. Alle Dateien müssen im gleichen Verzeichnis bleiben, damit relative Pfade funktionieren.
3. Für einen lokalen Test kann im Ordner `give_hope` `python -m http.server` ausgeführt und danach `http://localhost:8000` im Browser geöffnet werden.

## Logo und Farben
- Das Vereinslogo befindet sich im Projektstamm unter `kh2000.jpeg`. Es wird in der Navigationsleiste mit einem relativen Pfad (`../kh2000.jpeg`) eingebunden. Soll ein anderes Logo verwendet werden, das bestehende Bild ersetzen oder den Pfad in den HTML-Dateien anpassen.
- Die Farben des Vereins werden in `css/style.css` als Variablen `--primary-color`, `--secondary-color` und `--accent-color` definiert. Änderungen an diesen Variablen passen das Farbschema der gesamten Seite an.

## Bilder austauschen
- Alle aktuellen Bilder liegen im Unterordner `images`. Neue Bilder können dort abgelegt und in den HTML-Dateien referenziert werden.
- Es sollten nur Web‑Formate wie `.jpg` oder `.png` verwendet werden. Beim Austausch eines Bildes kann der Dateiname beibehalten oder in den entsprechenden `src`‑Attributen geändert werden.

## Beiträge und Inhalte ändern
- Startseitentexte befinden sich in `index.html`.
- Die Seite `about.html` enthält Vereinsinformationen und Teamvorstellung.
- Blogbeiträge werden in `blog.html` (Übersicht) und `blog-single.html` (Einzelbeitrag) gepflegt. Für einen neuen Beitrag die Struktur einer bestehenden Karte kopieren und Inhalte sowie Bildpfade anpassen.
- Kontaktinformationen stehen in `contact.html`.

## Social Media
- Links zu Facebook und Instagram befinden sich im Footer der HTML-Dateien. Zum Ändern der Adressen die URLs dort anpassen.

## Weitere Hinweise
- Es werden ausschließlich Online-Schriften verwendet, daher sind keine Schriftdateien erforderlich.
- Nach Änderungen sollte die Seite erneut lokal getestet werden, um Darstellungsfehler zu vermeiden.
