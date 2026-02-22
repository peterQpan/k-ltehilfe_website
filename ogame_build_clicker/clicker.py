"""Automatisierte Bau-Logik."""
from __future__ import annotations

import random
import time
from typing import Iterable

from .config import Config
from .ogame_client import OGameClient


class Clicker:
    """Führt Bauaufträge basierend auf der Konfiguration aus."""

    def __init__(self, client: OGameClient, config: Config) -> None:
        self.client = client
        self.config = config

    def run_cycle(self) -> None:
        """Durchläuft alle konfigurierten Planeten und startet Bauaufträge."""
        for planet_name, planet_cfg in self.config.planets.items():
            if not planet_cfg.automation_enabled:
                continue
            self.client.goto_planet(planet_name)
            # TODO: Hier Bauauswahl anhand von `planet_cfg.targets` implementieren
            self._human_delay(5, 15)

    @staticmethod
    def _human_delay(min_s: float, max_s: float) -> None:
        """Simuliert menschliche Pausen."""
        time.sleep(random.uniform(min_s, max_s))
