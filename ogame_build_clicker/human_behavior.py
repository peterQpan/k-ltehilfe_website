"""Simulation menschlichen Verhaltens."""

from __future__ import annotations

import asyncio
import random


async def zufaellige_wartezeit(min_ms: int = 500, max_ms: int = 1500) -> None:
    """Wartet eine zufällige Zeitspanne."""
    dauer = random.uniform(min_ms / 1000, max_ms / 1000)
    await asyncio.sleep(dauer)


async def seite_ansehen() -> None:
    """Simuliert das Ansehen einer Seite vor Bauabschluss."""
    await zufaellige_wartezeit(800, 1500)
    # Zusätzliche Scroll- oder Klickaktionen können hier ergänzt werden.
