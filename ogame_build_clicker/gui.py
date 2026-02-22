"""PySide6-GUI mit eingebettetem Browserfenster."""
from __future__ import annotations

from PySide6.QtWidgets import (
    QApplication,
    QMainWindow,
    QListWidget,
    QVBoxLayout,
    QWidget,
)

from .ogame_client import OGameClient


class MainWindow(QMainWindow):
    """Hauptfenster der Anwendung."""

    def __init__(self, client: OGameClient) -> None:
        super().__init__()
        self.client = client
        self.setWindowTitle("OGame Build Clicker")

        self.planet_list = QListWidget()
        layout = QVBoxLayout()
        layout.addWidget(self.planet_list)

        container = QWidget()
        container.setLayout(layout)
        self.setCentralWidget(container)

        # TODO: Planetenauswahl mit Browser synchronisieren


def run() -> None:
    """Startet die GUI-Anwendung."""
    app = QApplication([])
    client = OGameClient()
    client.open_start_page()

    window = MainWindow(client)
    window.show()
    app.exec()
    client.close()
