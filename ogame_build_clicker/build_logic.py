"""Bau-Logik für OGame."""

from __future__ import annotations

from typing import Any

from .config import Konfiguration, PlanetenKonfiguration
from .human_behavior import zufaellige_wartezeit, seite_ansehen


class BuildManager:
    """Steuert Bauaufträge basierend auf der Konfiguration."""

    def __init__(self, konfig: Konfiguration, seite: Any) -> None:
        self.konfig = konfig
        self.seite = seite

    async def bearbeite_planeten(self) -> None:
        for name, pkonfig in self.konfig.planeten.items():
            await self.wechsel_zu_planet(name)
            await self.baue_gebaeude(name, pkonfig)

    async def wechsel_zu_planet(self, name: str) -> None:
        """Wechselt im Browser auf den angegebenen Planeten."""
        # Navigation im Browser erfolgt hier.
        await zufaellige_wartezeit(300, 800)

    async def baue_gebaeude(self, planet: str, pkonfig: PlanetenKonfiguration) -> None:
        """Baut Gebäude gemäß der Ziel-Level."""
        for gebaeude, ziel in pkonfig.ziel_level.items():
            # Hier würde geprüft, ob das aktuelle Level < ziel ist.
            await seite_ansehen()
            # Klick auf den Bau-Button für das Gebäude.
            await zufaellige_wartezeit(200, 400)
