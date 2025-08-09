# Ingos kleine Kältehilfe - Website

## Übersicht
Diese Website wurde für "Ingos kleine Kältehilfe - Hand in Hand e.V." erstellt, eine Saarbrücker Initiative zur Unterstützung von obdachlosen und bedürftigen Menschen.

## Features
- **Responsive Design**: Funktioniert auf Desktop, Tablet und Smartphone
- **Mehrsprachig**: Deutsche Hauptversion mit englischer Übersetzung (Erweiterung für Türkisch, Italienisch, Spanisch vorbereitet)
- **Social Media Integration**: Direkte Verlinkung zu Facebook und Instagram
- **Bildergalerie**: Zeigt die Arbeit der Organisation in Bildern
- **Kontaktformular**: Einfache Kontaktaufnahme via E-Mail
- **Spendenintegration**: Verlinkung zur Spendenseite

## Technologie
- HTML5 und CSS3
- Bootstrap 5.2.2 für responsives Design
- Vanilla JavaScript (keine externen Abhängigkeiten)
- Vollständig statisch hostbar

## Installation und Hosting

### Einfaches Hosting (empfohlen)
1. Alle Dateien in den `k-hilfe/` Ordner auf Ihren Webserver hochladen
2. `index.html` als Startseite einrichten
3. Fertig! Die Website ist sofort verfügbar

### IONOS (1&1) Hosting
1. Via FTP alle Dateien in den `/` oder `/html/` Ordner hochladen
2. Die `index.html` wird automatisch als Startseite erkannt

### Lokales Testen
```bash
# Python 3
python3 -m http.server 8080

# Python 2
python -m SimpleHTTPServer 8080

# Node.js (wenn installiert)
npx serve
```

Dann http://localhost:8080 im Browser öffnen.

## Anpassungen

### Inhalte ändern
- **Text**: Direkt in `index.html` bearbeiten
- **Bilder**: In den `/images/` Ordner hochladen und Pfade in HTML anpassen
- **Logo**: `images/logo-kaeltehilfe.jpeg` ersetzen

### Kontaktdaten ändern
Suchen und ersetzen in `index.html`:
- E-Mail: `hand-in-hand@gmx.de`
- Telefon: `+491749117841`
- Adresse: `Stengelstr. 30, 66117 Saarbrücken`

### Farben anpassen
In `css/templatemo-kind-heart-charity.css` die CSS-Variablen ändern:
```css
:root {
  --primary-color: #e50510;     /* Hauptfarbe */
  --secondary-color: #1ca1a0;   /* Sekundärfarbe */
  --accent-color: #feae01;      /* Akzentfarbe */
}
```

### Weitere Sprachen hinzufügen
1. `index.html` kopieren (z.B. `index-tr.html` für Türkisch)
2. Texte übersetzen
3. Sprachumschalter in der Navigation erweitern

## Social Media
- **Facebook**: https://www.facebook.com/p/Ingos-kleine-K%C3%A4ltehilfe-100064428263297/
- **Instagram**: https://instagram.com/ingoskleinekaeltehilfe

## Support
Bei technischen Fragen oder Anpassungswünschen wenden Sie sich an den Entwickler oder die lokale IT-Community.

## Lizenz
Das Website-Template stammt von TemplateMo und ist kostenlos für gemeinnützige Zwecke nutzbar.
Anpassungen und Inhalte: © 2025 Ingos kleine Kältehilfe - Hand in Hand e.V.