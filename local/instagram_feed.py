#!/usr/bin/env python3
import os
import json
import requests

def fetch_instagram_images(username, limit=5, outdir="bilder"):
    """
    Lädt bis zu `limit` Bilder eines öffentlichen Instagram-Kontos herunter.
    Verwendet den aktuellen inoffiziellen API-Endpunkt (2025-kompatibel).
    """
    url = f"https://i.instagram.com/api/v1/users/web_profile_info/?username={username}"
    headers = {
        "x-ig-app-id": "936619743392459",  # Feste Instagram-Web-App-ID (erforderlich)
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
        "Accept-Language": "en-US,en;q=0.9",
        "Accept-Encoding": "gzip, deflate, br",
        "Accept": "*/*",
    }
    r = requests.get(url, headers=headers)
    r.raise_for_status()  # Fehler werfen, wenn Request fehlschlägt

    data = r.json()
    edges = data["data"]["user"]["edge_owner_to_timeline_media"]["edges"]

    urls = []

    for idx, edge in enumerate(edges[:limit]):
        img_url = edge["node"]["display_url"]
        urls.append(img_url)

    return urls

if __name__ == "__main__":
    urls = fetch_instagram_images("ingoskleinekaeltehilfe", limit=15)
    print(json.dumps(urls, indent=2))