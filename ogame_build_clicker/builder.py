"""Automatisierte Bau-Logik mit menschlicher Simulation."""
from __future__ import annotations

import asyncio
import random
from dataclasses import dataclass
from typing import Dict

from playwright.async_api import async_playwright, Page

from .config import Config


@dataclass
class BuildTask:
    planet: str
    building: str


class BuildManager:
    def __init__(self, config: Config):
        self.config = config
        self._playwright = None
        self.page: Page | None = None

    async def start(self) -> None:
        self._playwright = await async_playwright().start()
        browser = await self._playwright.chromium.launch(headless=False)
        context = await browser.new_context()
        self.page = await context.new_page()
        await self.page.goto("https://ogame.gameforge.com/")
        # Login erfolgt manuell im geöffneten Fenster.

    async def stop(self) -> None:
        if self._playwright:
            await self._playwright.stop()

    async def simulate_human_view(self, min_delay: int = 5, max_delay: int = 15) -> None:
        delay = random.uniform(min_delay, max_delay)
        await asyncio.sleep(delay)

    async def build(self) -> None:
        if not self.page:
            return
        for planet_name, planet in self.config.planets.items():
            await self.select_planet(planet_name)
            for building, target in planet.buildings.items():
                await self.handle_building(building, target.level)

    async def select_planet(self, planet_name: str) -> None:
        if not self.page:
            return
        # Platzhalter: Annahme, dass ein Planet per Text gefunden werden kann
        await self.page.click(f"text={planet_name}")
        await self.simulate_human_view()

    async def handle_building(self, building: str, target_level: int) -> None:
        if not self.page:
            return
        # Platzhalter für Navigationslogik
        await self.page.click(f"text={building}")
        await self.simulate_human_view(3, 10)
        # Hier müsste der aktuelle Level ausgelesen werden
        # und ggf. der Bau gestartet werden
        await self.page.click("text=Ausbauen", timeout=5000)
        await self.simulate_human_view()
