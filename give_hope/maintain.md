# Pflegeanleitung

## Hosting
- Lege den Inhalt des Ordners `give_hope` auf einen Webserver oder nutze einen einfachen Entwicklungsserver:
  ```bash
  cd give_hope
  python -m http.server
  ```
- Öffne anschließend `http://localhost:8000` im Browser.
- Achte darauf, dass sich die Datei `kh2000.jpeg` eine Ebene oberhalb des Ordners befindet oder passe den Pfad im Navbar-Logo an.

## Bilder austauschen
- Bilder liegen im Verzeichnis `images/` bzw. als Logo `kh2000.jpeg` im Projektwurzelverzeichnis.
- Ersetze eine Bilddatei durch eine neue Datei mit gleichem Namen oder aktualisiere den Pfad in den HTML-Dateien.
- Füge neue Bilder nicht per Pull Request hinzu, sondern lade sie direkt auf dem Server hoch.

## Beiträge ändern und hinzufügen
- Blog-Übersicht: `blog.html`
- Einzelner Beitrag: `blog-single.html`
- Kopiere einen bestehenden `<div class="post-entry">` Block in `blog.html`, passe Titel, Bild und Link an.
- Für neue Beiträge dupliziere `blog-single.html` und verlinke ihn entsprechend.

## Texte und Farben
- Allgemeine Texte befinden sich in den HTML-Dateien im Ordner `give_hope`.
- Die Vereinsfarben sind am Anfang der Datei `css/style.css` als CSS-Variablen definiert:
  ```css
  :root {
    --primary-color: #1ca1a0;
    --secondary-color: #0788cc;
    --accent-color: #feae01;
  }
  ```
- Passe bei Bedarf diese Werte an, um das Farbschema zu ändern.

## Soziale Medien
- Die Links zu Facebook und Instagram stehen im Footer jedes HTML-Dokuments.
- Aktualisiere sie bei Änderungen der Accounts.
