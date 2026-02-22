"""Einstiegspunkt der Anwendung."""

from __future__ import annotations

import asyncio
import sys

from PySide6.QtWidgets import QApplication, QMainWindow, QPushButton, QVBoxLayout, QWidget
from playwright.async_api import async_playwright

from .config import Konfiguration, lade_konfiguration, speichere_konfiguration
from .build_logic import BuildManager


class HauptFenster(QMainWindow):
    def __init__(self) -> None:
        super().__init__()
        self.setWindowTitle("OGame Build-Clicker")
        self.konfig: Konfiguration = lade_konfiguration()

        self.start_knopf = QPushButton("Browser starten")
        self.start_knopf.clicked.connect(self._start_browser)

        layout = QVBoxLayout()
        layout.addWidget(self.start_knopf)
        zentrum = QWidget()
        zentrum.setLayout(layout)
        self.setCentralWidget(zentrum)

    def closeEvent(self, ereignis) -> None:  # type: ignore[override]
        speichere_konfiguration(self.konfig)
        super().closeEvent(ereignis)

    def _start_browser(self) -> None:
        asyncio.create_task(self._async_browser())

    async def _async_browser(self) -> None:
        async with async_playwright() as p:
            browser = await p.chromium.launch(headless=False)
            seite = await browser.new_page()
            await seite.goto("https://lobby.ogame.gameforge.com/de_DE/")
            manager = BuildManager(self.konfig, seite)
            await manager.bearbeite_planeten()


def main() -> None:
    app = QApplication(sys.argv)
    fenster = HauptFenster()
    fenster.show()
    asyncio.get_event_loop().run_forever()


if __name__ == "__main__":
    main()
