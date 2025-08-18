"""Konfigurationsverwaltung für den Build-Clicker."""
from __future__ import annotations

from dataclasses import dataclass, field
from pathlib import Path
import json
from typing import Dict, Any

CONFIG_FILE = Path("config.json")


@dataclass
class BuildingTarget:
    level: int = 0
    auto: bool = True


@dataclass
class PlanetConfig:
    name: str
    buildings: Dict[str, BuildingTarget] = field(default_factory=dict)


@dataclass
class Config:
    planets: Dict[str, PlanetConfig] = field(default_factory=dict)

    @classmethod
    def load(cls, file: Path = CONFIG_FILE) -> "Config":
        if file.exists():
            data = json.loads(file.read_text())
            planets = {
                name: PlanetConfig(
                    name=name,
                    buildings={k: BuildingTarget(**v) for k, v in p.get("buildings", {}).items()},
                )
                for name, p in data.get("planets", {}).items()
            }
            return cls(planets=planets)
        return cls()

    def save(self, file: Path = CONFIG_FILE) -> None:
        data: Dict[str, Any] = {
            "planets": {
                name: {
                    "buildings": {bk: vars(bv) for bk, bv in pc.buildings.items()}
                }
                for name, pc in self.planets.items()
            }
        }
        file.write_text(json.dumps(data, indent=2))
