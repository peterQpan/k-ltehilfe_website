# Pflege und Deployment der Webseite

## Hosting
1. Lade den Inhalt des Ordners `charity` auf einen Webserver oder einen statischen Hosting-Dienst (z.B. GitHub Pages, Netlify).
2. Stelle sicher, dass die Datei `index.html` als Startseite genutzt wird.
3. Das Projekt benötigt keine zusätzlichen Build-Schritte; die HTML-, CSS- und JS-Dateien können direkt ausgeliefert werden.

## Bilder austauschen
1. Ersetze bestehende Bilder durch neue Dateien mit gleichem Namen und Format, um Verlinkungen beizubehalten.
2. Alternativ kann der `src`-Pfad in den HTML-Dateien angepasst werden. Achte darauf, keine binären Dateien in Pull-Requests hochzuladen.
3. Das Vereinslogo befindet sich eine Ebene über dem `charity`-Ordner (`../kh2000.jpeg`).

## Inhalte bearbeiten
- **Texte:** Die Inhalte sind in den HTML-Dateien hinterlegt. Ändere Überschriften oder Absätze direkt dort.
- **Beiträge:** Neue Beiträge können durch Kopieren vorhandener `news-detail.html`-Abschnitte angelegt werden. Verlinke sie in `news.html`.
- **Farben:** Zentrale Farben werden in `css/templatemo-kind-heart-charity.css` über CSS-Variablen gepflegt.

## Social Media
Die Links zu Facebook und Instagram sind in Kopf- und Fußzeile hinterlegt. Passe sie bei Bedarf in den HTML-Dateien an.

## Lokale Vorschau
1. Öffne die Datei `index.html` im Browser, um Änderungen lokal zu prüfen.
2. Für einen einfachen lokalen Server kann z.B. `python3 -m http.server` im `charity`-Ordner genutzt werden.

