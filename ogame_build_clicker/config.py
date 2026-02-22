"""Konfigurationsverwaltung für den Build-Clicker."""
from __future__ import annotations

from dataclasses import dataclass, field, asdict
from pathlib import Path
from typing import Dict
import json

CONFIG_FILE = Path(__file__).with_name("config.json")


@dataclass
class PlanetConfig:
    """Konfiguration für einen einzelnen Planeten."""

    targets: Dict[str, int] = field(default_factory=dict)
    automation_enabled: bool = True


@dataclass
class Config:
    """Globale Konfiguration."""

    planets: Dict[str, PlanetConfig] = field(default_factory=dict)


def load_config(path: Path = CONFIG_FILE) -> Config:
    """Lädt die Konfiguration aus einer JSON-Datei."""
    if path.exists():
        raw = json.loads(path.read_text(encoding="utf-8"))
        planets = {
            name: PlanetConfig(**planet) for name, planet in raw.get("planets", {}).items()
        }
        return Config(planets=planets)
    return Config()


def save_config(config: Config, path: Path = CONFIG_FILE) -> None:
    """Speichert die Konfiguration als JSON-Datei."""
    data = {"planets": {name: asdict(cfg) for name, cfg in config.planets.items()}}
    path.write_text(
        json.dumps(data, indent=2, ensure_ascii=False), encoding="utf-8"
    )
