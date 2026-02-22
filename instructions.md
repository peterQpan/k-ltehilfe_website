# Projektanleitung

Dieses Repository enthält einen experimentellen Build-Clicker für das Spiel OGame sowie zugehörige Dokumentation.

## Struktur
- `ogame.md`: Zusammenfassung des Gameplays.
- `architecture.md`: Modulaufbau der Anwendung.
- `no_bot.md`: Strategien gegen Bot-Erkennung.
- `ki.md`: Ideen zur KI-Integration.
- `ogame_build_clicker/`: Python-Code des Clickers.

## Installation
1. Python 3.10+ verwenden.
2. Abhängigkeiten installieren:
   ```bash
   pip install -r ogame_build_clicker/requirements.txt
   playwright install
   ```

## Verwendung
```bash
python -m ogame_build_clicker.main
```

- Im eingebetteten Browser manuell bei OGame einloggen.
- Planeten und Ziele derzeit nur in `config.json` definierbar (Automatisierung rudimentär).

## Weiterentwicklung
- Bau-Logik in `clicker.py` vervollständigen.
- GUI um Konfigurationseditor erweitern.
- KI-Module gemäß `ki.md` erforschen.
