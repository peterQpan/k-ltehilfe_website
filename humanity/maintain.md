# Pflege der Website "Ingos kleine Kältehilfe"

## Hosting
1. Stelle sicher, dass alle Dateien im Ordner `humanity` liegen und statisch ausgeliefert werden können.
2. Zum lokalen Testen kann ein einfacher Webserver genutzt werden:
   ```bash
   cd humanity
   python3 -m http.server 8000
   ```
   Die Seite ist anschließend unter <http://localhost:8000> erreichbar.
3. Auf einem Live‑Server reicht es, den Inhalt des Ordners `humanity` in den Webroot zu kopieren oder über einen Hostingdienst für statische Seiten bereitzustellen.

## Logo und Bilder austauschen
- Das Vereinslogo liegt unter `kh2000.jpeg` im Repository‑Root. In allen HTML‑Dateien wird es relativ mit `../kh2000.jpeg` eingebunden.
- Soll ein neues Logo oder weitere Bilder verwendet werden, lege sie außerhalb des `humanity`‑Ordners ab oder verlinke auf eine externe Quelle, damit keine Binärdateien ins Repository gelangen.
- Passe die Pfade in den HTML‑Dateien entsprechend an.

## Farben anpassen
- Zentrale Farbvariablen befinden sich oben in `css/styles.css` (`--primary-color`, `--secondary-color`, `--accent-color`).
- Ändere die Hex‑Werte, um das Farbschema zu wechseln.

## Inhalte ändern und Beiträge hinzufügen
- Textänderungen nimmst du direkt in den jeweiligen HTML‑Dateien vor (z. B. `index.html`, `about.html`, `contact.html`).
- Blogbeiträge auf der Startseite befinden sich im Abschnitt `<section class="blog">` in `index.html`. Du kannst vorhandene Blöcke duplizieren oder ersetzen.
- Achte darauf, Überschriften und Absätze auf Deutsch zu formulieren und gegebenenfalls Links anzupassen.

## Social‑Media‑Links
- Die Links zu Facebook und Instagram befinden sich im Footer jeder Seite innerhalb des Elements `div.social-share`.
- Aktualisiere die `href`‑Attribute, falls sich die URLs ändern.

## Allgemeine Hinweise
- Nach Änderungen die Seite erneut im Browser testen.
- Keine Binärdateien (Bilder, Fonts usw.) in Commit aufnehmen; verlinke stattdessen externe Ressourcen.
