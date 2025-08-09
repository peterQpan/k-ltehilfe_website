# Konzept: Kältehilfe Webseite

## Ziel
Eine einfache, leicht hostbare Webseite für die Kältehilfe-Initiative. Sie informiert über Ziele und Angebote der Initiative und zeigt Besucher*innen, wie sie helfen können.

## Seitenstruktur
- **Startseite (index.html):** Vorstellung, Aufruf zur Unterstützung, Abschnitte zu "Über uns", "So kannst du helfen" und Kontakt.
- **Impressum (impressum.html):** Rechtliche Angaben gemäß deutscher Gesetzgebung.
- **Datenschutz (datenschutz.html):** Hinweise zum Umgang mit personenbezogenen Daten.

## Technologie
- Reines HTML und CSS (keine Build-Tools, kein Server-Side-Code).
- Responsives Design für mobile und Desktop.
- Keine externen Abhängigkeiten, um einfache Bereitstellung z. B. bei IONOS über FTP zu ermöglichen.

## Deployment
- Dateien per FTP/SSH auf beliebigen Hoster hochladen.
- Keine Datenbank oder serverseitige Skripte nötig.

## Mögliche Schwachstellen und Lösungen
1. **Kontaktformular ohne Backend:** Ohne Serverskript lassen sich Anfragen nicht speichern. Lösung: Nutzung von `mailto:`-Links oder externen Formular-Diensten.
2. **Spendenabwicklung:** Ohne Zahlungsabwicklung nur Bankdaten oder Links zu externen Diensten möglich.
3. **Datenschutz:** Nur minimale Erfassung von Daten, Hinweise im Datenschutz-Dokument.
4. **Barrierefreiheit:** Semantisches HTML, Kontraste und Alt-Texte beachten.

## Nächste Schritte
1. Erstellung der HTML-Seiten und des CSS.
2. Verlinkung der Seiten untereinander.
3. Validierung der HTML-Dateien mit einem Validator.
4. Anleitung zum Hochladen in der README.
