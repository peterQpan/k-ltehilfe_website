# Nutzung und Weiterentwicklung

Dieses Projekt enthält die Grundlagen für einen Build-Clicker für OGame.

## Struktur
- Dokumentation: `ogame.md`, `architecture.md`, `no_bot.md`, `ki.md`
- Code: Ordner `ogame_build_clicker`

## Voraussetzungen
- Python 3.11+
- Abhängigkeiten: `PySide6`, `playwright`
- Nach der Installation von Playwright müssen die Browser-Binaries mit `playwright install` eingerichtet werden.

## Start
1. `python -m ogame_build_clicker.main` ausführen.
2. Im erscheinenden Fenster den Browser starten und manuell bei OGame anmelden.
3. Die Konfiguration wird in `config.json` gespeichert und beim nächsten Start geladen.

## Weiterentwicklung
- Die Module sind bewusst einfach gehalten und können für weitere Automatisierungslogik erweitert werden.
- Für die Simulation menschlicher Aktionen sollten `human_behavior` und `build_logic` angepasst werden.
