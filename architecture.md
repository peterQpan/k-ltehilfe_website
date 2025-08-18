# Architekturplanung

Die Anwendung **ogame_build_clicker** ist modular aufgebaut und soll leicht erweiterbar sein.

## Module
- `config`: Laden und Speichern der Konfiguration.
- `human_behavior`: Funktionen zur Simulation menschlicher Aktionen.
- `build_logic`: Zentrale Logik zum Steuern der Bauaufträge.
- `main`: GUI und Integration des Browsers über Playwright.

## Datenflüsse
1. Beim Start wird die Konfiguration geladen.
2. Der Benutzer startet den Browser manuell über die GUI.
3. `build_logic` prüft anhand der Konfiguration, welche Gebäude bis zu welchem Level gebaut werden sollen.
4. `human_behavior` sorgt für realistische Verzögerungen und Seitenaufrufe.
5. Nach Abschluss werden neue Bauaufträge gesetzt.

## Persistenz
Die Konfiguration wird als `config.json` im Projektverzeichnis gespeichert.
