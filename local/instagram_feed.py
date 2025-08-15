import argparse
import os
import requests


def fetch_instagram_images(username: str, dest_folder: str = "instagram_images", limit: int = 12) -> None:
    """Download the latest images from a public Instagram profile."""
    headers = {"User-Agent": "Mozilla/5.0"}
    url = f"https://www.instagram.com/{username}/?__a=1&__d=dis"
    response = requests.get(url, headers=headers, timeout=10)
    response.raise_for_status()
    data = response.json()

    edges = data["graphql"]["user"]["edge_owner_to_timeline_media"]["edges"]

    os.makedirs(dest_folder, exist_ok=True)
    for edge in edges[:limit]:
        display_url = edge["node"]["display_url"]
        img_response = requests.get(display_url, headers=headers, timeout=10)
        img_response.raise_for_status()
        filename = os.path.join(dest_folder, display_url.split("?")[0].split("/")[-1])
        with open(filename, "wb") as f:
            f.write(img_response.content)
        print(f"saved {filename}")


def main() -> None:
    parser = argparse.ArgumentParser(description="Download images from an Instagram feed")
    parser.add_argument("username", help="Instagram username")
    parser.add_argument("--dest", default="instagram_images", help="Directory to store images")
    parser.add_argument("--limit", type=int, default=12, help="Number of images to download")
    args = parser.parse_args()
    fetch_instagram_images(args.username, args.dest, args.limit)


if __name__ == "__main__":
    main()
