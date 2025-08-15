// Fetch latest Instagram posts and render gallery

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('instagram-feed');
    if (!container) {
        return;
    }

    fetch('/instagram-feed.php')
        .then((response) => response.json())
        .then((items) => {
            if (!Array.isArray(items)) {
                throw new Error('Invalid data');
            }
            items.forEach((item) => {
                if (!item.thumbnail || !item.link) {
                    return;
                }
                const a = document.createElement('a');
                a.href = item.link;
                a.target = '_blank';
                const img = document.createElement('img');
                img.src = item.thumbnail;
                img.alt = 'Instagram Image';
                a.appendChild(img);
                container.appendChild(a);
            });
        })
        .catch((err) => {
            console.error('Instagram feed error:', err);
            container.innerText = 'Instagram Feed konnte nicht geladen werden.';
        });
});
