# Architektur und Modulstruktur

Die Anwendung ist als modularer Build-Clicker konzipiert.

## Module
- **gui.py**: PySide6-GUI mit eingebettetem Browserfenster. Der Benutzer loggt sich manuell bei OGame ein. Planetenauswahl in der GUI synchronisiert sich mit dem Browser.
- **ogame_client.py**: Wrapper um Playwright für Navigation und DOM-Interaktion.
- **config.py**: Lädt und speichert Konfigurationen pro Planet und Gebäude (Zielstufen, Optionen).
- **clicker.py**: Logik zum Bau der Gebäude basierend auf Konfiguration und OGame-Zustand. Enthält Simulation menschlichen Verhaltens.
- **main.py**: Einstiegspunkt. Initialisiert Konfiguration, GUI und Clicker.

## Datenfluss
1. `main.py` lädt Konfiguration und startet GUI.
2. `ogame_client.py` steuert das Browserfenster (Playwright mit `headless=False`).
3. Benutzer wählt Planet in der GUI; Auswahl wird an `ogame_client` weitergegeben.
4. `clicker.py` prüft Bauaufträge und löst nötige Klicks aus.
5. Verzögerungen und zufällige Pausen simulieren menschliche Aktionen.

## Erweiterbarkeit
- Neue Strategien oder Ressourcenlogik können als weitere Module ergänzt werden.
- KI-Module (siehe `ki.md`) können den Clicker um intelligente Entscheidungen erweitern.
