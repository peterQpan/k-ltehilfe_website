"""Wrapper um Playwright für OGame-Interaktionen."""
from __future__ import annotations

from typing import Optional
from playwright.sync_api import sync_playwright, Page


class OGameClient:
    """Steuert einen Browser über Playwright."""

    def __init__(self) -> None:
        self._play = sync_playwright().start()
        self._browser = self._play.chromium.launch(headless=False)
        self.page: Page = self._browser.new_page()

    def open_start_page(self) -> None:
        """Öffnet die OGame-Lobby. Login erfolgt manuell."""
        self.page.goto("https://lobby.ogame.gameforge.com/de_DE")

    def goto_planet(self, planet_id: str) -> None:
        """Wechselt zu einem Planeten. Platzhalter, da die DOM-Struktur projektabhängig ist."""
        # TODO: Implementiere DOM-Selektoren basierend auf Beispielscreenshots
        _ = planet_id

    def close(self) -> None:
        """Schließt Browser und Playwright."""
        self._browser.close()
        self._play.stop()
