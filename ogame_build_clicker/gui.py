"""PySide6-Oberfläche mit Browser-Screenshot."""
from __future__ import annotations

import asyncio
from pathlib import Path
from typing import Optional

from PySide6.QtCore import QTimer
from PySide6.QtGui import QPixmap
from PySide6.QtWidgets import (
    QApplication,
    QLabel,
    QMainWindow,
    QListWidget,
    QPushButton,
    QVBoxLayout,
    QWidget,
)

from .builder import BuildManager
from .config import Config


class BrowserWidget(QLabel):
    def __init__(self, manager: BuildManager):
        super().__init__("Browser wird geladen...")
        self.manager = manager
        self.timer = QTimer()
        self.timer.timeout.connect(self.update_view)
        self.timer.start(2000)

    def update_view(self) -> None:
        if self.manager.page:
            path = Path(".screenshot.png")
            asyncio.create_task(self.manager.page.screenshot(path=path))
            if path.exists():
                self.setPixmap(QPixmap(str(path)))


class MainWindow(QMainWindow):
    def __init__(self, config: Config, manager: BuildManager):
        super().__init__()
        self.setWindowTitle("OGame Build-Clicker")
        self.config = config
        self.manager = manager

        self.browser_widget = BrowserWidget(manager)
        self.planet_list = QListWidget()
        for name in config.planets:
            self.planet_list.addItem(name)
        self.planet_list.currentTextChanged.connect(self.on_planet_selected)

        self.start_button = QPushButton("Bau starten")
        self.start_button.clicked.connect(self.start_building)

        layout = QVBoxLayout()
        layout.addWidget(self.browser_widget)
        layout.addWidget(self.planet_list)
        layout.addWidget(self.start_button)

        container = QWidget()
        container.setLayout(layout)
        self.setCentralWidget(container)

    def on_planet_selected(self, name: str) -> None:
        asyncio.create_task(self.manager.select_planet(name))

    def start_building(self) -> None:
        asyncio.create_task(self.manager.build())


def run() -> None:
    config = Config.load()
    manager = BuildManager(config)

    async def async_main() -> None:
        await manager.start()

    app = QApplication([])
    window = MainWindow(config, manager)
    window.resize(800, 600)
    window.show()

    loop = asyncio.get_event_loop()
    loop.create_task(async_main())
    loop.run_until_complete(loop.create_task(app.exec()))


if __name__ == "__main__":
    run()
