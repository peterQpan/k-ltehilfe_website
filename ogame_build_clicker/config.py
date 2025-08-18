"""Verwaltung der Konfiguration."""

from __future__ import annotations

from dataclasses import dataclass, field
from pathlib import Path
from typing import Dict
import json

CONFIG_DATEI = Path("config.json")


@dataclass
class PlanetenKonfiguration:
    ziel_level: Dict[str, int] = field(default_factory=dict)


@dataclass
class Konfiguration:
    planeten: Dict[str, PlanetenKonfiguration] = field(default_factory=dict)
    mischbauweise: bool = True
    auto_energie: bool = True


def lade_konfiguration(pfad: Path = CONFIG_DATEI) -> Konfiguration:
    """Liest die Konfiguration von der Festplatte."""
    if pfad.exists():
        daten = json.loads(pfad.read_text(encoding="utf-8"))
        planeten = {
            name: PlanetenKonfiguration(**cfg)
            for name, cfg in daten.get("planeten", {}).items()
        }
        return Konfiguration(
            planeten=planeten,
            mischbauweise=daten.get("mischbauweise", True),
            auto_energie=daten.get("auto_energie", True),
        )
    return Konfiguration()


def speichere_konfiguration(konfig: Konfiguration, pfad: Path = CONFIG_DATEI) -> None:
    """Speichert die Konfiguration als JSON."""
    daten = {
        "planeten": {name: vars(cfg) for name, cfg in konfig.planeten.items()},
        "mischbauweise": konfig.mischbauweise,
        "auto_energie": konfig.auto_energie,
    }
    pfad.write_text(json.dumps(daten, indent=2, ensure_ascii=False), encoding="utf-8")
