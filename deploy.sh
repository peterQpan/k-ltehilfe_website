#!/bin/bash

# Deployment-Script für Hugo Website mit PHP-Unterstützung
# Kopiert notwendige Dateien nach dem Hugo-Build

echo "🚀 Starte Deployment-Vorbereitung..."

# 1. Hugo Build
echo "📦 Hugo Build wird ausgeführt..."
hugo --minify

# 2. Vendor-Verzeichnis kopieren  
echo "📚 Kopiere PHP-Dependencies..."
if [ -d "vendor" ]; then
    cp -r vendor public/
    echo "✅ vendor/ nach public/ kopiert"
else
    echo "❌ vendor/ Verzeichnis nicht gefunden - führe 'composer install' aus"
    exit 1
fi

# 3. .env-Datei kopieren (falls vorhanden)
if [ -f ".env" ]; then
    cp .env public/
    echo "✅ .env nach public/ kopiert"
else
    echo "⚠️  .env nicht gefunden - SMTP wird nicht funktionieren"
fi

# 4. Berechtigungen setzen
echo "🔐 Setze Berechtigungen..."
chmod 755 public/mail.php
chmod 755 public/instagram.php
chmod 755 public/image_proxy.php

# 5. Validierung
echo "🔍 Validiere Deployment..."
if [ -f "public/vendor/autoload.php" ]; then
    echo "✅ PHPMailer Autoload verfügbar"
else
    echo "❌ PHPMailer Autoload NICHT verfügbar"
fi

if [ -f "public/.env" ]; then
    echo "✅ Konfigurationsdatei verfügbar"
else
    echo "⚠️  Konfigurationsdatei fehlt"
fi

echo "🎉 Deployment-Vorbereitung abgeschlossen!"
echo ""
echo "📋 Nächste Schritte:"
echo "1. Erstelle .env-Datei mit SMTP-Zugangsdaten"
echo "2. Lade public/ Verzeichnis auf deinen Webserver hoch"
echo "3. Stelle sicher, dass PHP auf dem Server läuft"