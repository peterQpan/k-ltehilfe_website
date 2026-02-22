# Architekturplan

Dieses Dokument beschreibt die modulare Struktur des geplanten Build‑Clickers.

## Module
- `gui`: PySide6-Oberfläche mit eingebettetem Browserfenster.
- `config`: Laden und Speichern der Konfiguration (Ziel-Level pro Planet/Gebäude).
- `builder`: Logik für automatisierten Bau und menschliche Verzögerungen.
- `main`: Einstiegspunkt, verbindet GUI und Builder.

## Datenfluss
1. Beim Start lädt `config` eine JSON-Datei mit Planetendaten.
2. Die GUI zeigt Planeten und Zielwerte, ermöglicht manuelle Anpassungen.
3. `builder` überwacht Baufortschritt via Playwright und triggert neue Bauaufträge.
4. Änderungen werden wieder in der Konfigurationsdatei persistiert.

## Erweiterbarkeit
Die modulare Struktur ermöglicht das Ergänzen weiterer Automatisierungsoptionen oder KI-Module ohne großen Eingriff in bestehende Komponenten.
