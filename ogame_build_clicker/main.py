"""Einstiegspunkt für den Build-Clicker."""
from __future__ import annotations

from .config import load_config, save_config
from .gui import run


def main() -> None:
    """Startet die Anwendung und speichert die Konfiguration beim Beenden."""
    cfg = load_config()
    # TODO: Konfiguration in GUI anzeigen und editierbar machen
    run()
    save_config(cfg)


if __name__ == "__main__":  # pragma: no cover - Skriptstart
    main()
