# Wartung und Anpassung der Website

## Hosting
1. Installiere einen beliebigen statischen Webserver (z.B. [serve](https://www.npmjs.com/package/serve) oder Apache).
2. Kopiere den Inhalt des Ordners `humanity` auf den Server.
3. Stelle sicher, dass die Datei `kh2000.jpeg` im Projektstamm liegt und von den HTML-Dateien über `../kh2000.jpeg` erreichbar ist.
4. Starte den Server und rufe `index.html` im Browser auf.

## Bilder austauschen
- Ersetze bestehende Bilder im Ordner `humanity/img` bzw. `humanity/images` durch neue Dateien mit gleichem Namen.
- Alternativ kannst du Pfade in den HTML-Dateien anpassen, ohne neue Binärdateien zu committen.

## Farben anpassen
- Die zentralen Farben sind in `humanity/css/styles.css` definiert.
- Die Vereinsfarben sind: `#e50510` (Primär) und `#1ca1a0` (Sekundär).
- Weitere Farbanpassungen sollten sparsam und harmonisch vorgenommen werden.

## Texte ändern und Beiträge hinzufügen
- HTML-Dateien befinden sich direkt im Ordner `humanity`.
- Für neue Blogbeiträge im Startseiten-Bereich `section.blog` einen neuen Block nach dem bestehenden Muster einfügen.
- Achte darauf, alle Texte auf Deutsch zu verfassen und bei Bedarf Daten aus `history.md` zu übernehmen.

## Soziale Medien
- Die Links zu Facebook und Instagram befinden sich im Footer jeder Seite.
- Bei Änderungen die URLs in allen HTML-Dateien aktualisieren.

## Kontaktinformationen
- Adresse, Telefon und E-Mail stehen in `humanity/contact.html` und im Footer.
- Aktualisiere diese Daten konsistent in allen Dateien.

## Lokale Tests
- Starte in der Projektwurzel einen lokalen Server, z.B.:
  ```bash
  python3 -m http.server 8000
  ```
- Rufe danach im Browser `http://localhost:8000/humanity/` auf und prüfe alle Seiten.
