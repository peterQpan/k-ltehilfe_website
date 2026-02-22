# KI-Integrationsstudie

Ziel dieser Studie ist es, die spätere Einbindung einer KI zur strategischen Planung des Baufortschritts zu untersuchen.

## Mögliche Ansätze
- **Regelbasierte Systeme**: vordefinierte Build-Orders je nach Planetentyp.
- **Reinforcement Learning**: Training einer KI, die optimale Bauentscheidungen basierend auf Ressourcenzuständen trifft.
- **Planungsalgorithmen**: Nutzung heuristischer Verfahren zur Auswahl des nächsten Gebäudes.

## Schnittstellen
- Die KI erhält aus `config` und `builder` Informationen über den aktuellen Zustand.
- Entscheidungen der KI werden als Ziel-Level in die Konfiguration zurückgeschrieben.

## Herausforderungen
- Begrenzte Datenbasis und wechselnde OGame-Versionen erschweren das Training.
- Bot-Erkennung darf nicht ausgelöst werden; KI muss menschliche Muster berücksichtigen.
