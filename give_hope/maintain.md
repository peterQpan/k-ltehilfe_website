# Pflege der Website "Ingos kleine Kältehilfe"

## Hosting
1. Stelle sicher, dass alle Dateien im Ordner `give_hope` liegen und statisch ausgeliefert werden können.
2. Zum lokalen Testen:
   ```bash
   cd give_hope
   python3 -m http.server 8000
   ```
   Die Seite ist anschließend unter <http://localhost:8000> erreichbar.
3. Für den Live‑Betrieb den Inhalt des Ordners `give_hope` in den Webroot kopieren oder über einen Dienst für statische Seiten (z. B. GitHub Pages, Netlify) bereitstellen.

## Logo und Bilder austauschen
- Das Vereinslogo liegt als `kh2000.jpeg` im Repository‑Root und wird in den HTML‑Dateien relativ mit `../kh2000.jpeg` eingebunden.
- Neue Bilder oder ein anderes Logo sollten außerhalb des `give_hope`‑Ordners abgelegt oder von externen Quellen verlinkt werden, damit keine zusätzlichen Binärdateien in das Repository gelangen.
- Passe die `src`‑Attribute in den HTML‑Dateien entsprechend an.

## Farben anpassen
- Zentrale Farbvariablen stehen oben in `css/style.css` (`--primary-color`, `--secondary-color`, `--accent-color`).
- Ändere die Hex‑Werte dieser Variablen, um das Farbschema zu aktualisieren.

## Inhalte ändern und Beiträge hinzufügen
- Texte werden direkt in den jeweiligen HTML‑Dateien (z. B. `index.html`, `about.html`, `contact.html`) angepasst.
- Blogbeiträge auf der Startseite findest du im Abschnitt `<section class="blog">` in `index.html`. Bestehende Blöcke können dupliziert oder ersetzt werden.
- Achte darauf, Überschriften und Absätze auf Deutsch zu formulieren und Links anzupassen.

## Social‑Media‑Links
- Im Footer jeder Seite befinden sich Links zu Facebook und Instagram innerhalb der Liste unter "Kontaktdaten".
- Aktualisiere die `href`‑Attribute, falls sich die URLs ändern.

## Allgemeine Hinweise
- Nach jeder Änderung die Seite im Browser testen.
- Keine Binärdateien (Bilder, Fonts usw.) in Commits aufnehmen; verlinke stattdessen externe Ressourcen.
