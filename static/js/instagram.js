document.addEventListener('DOMContentLoaded', function () {
    fetch('/instagram.php')
        .then(function (response) {
            return response.json();
        })
        .then(function (images) {
            var container = document.getElementById('instagram-feed');
            if (!container) {
                return;
            }
            images.forEach(function (url) {
                var col = document.createElement('div');
                col.className = 'col-lg-2 col-md-3 col-4 mb-3';

                var link = document.createElement('a');
                link.href = url;
                link.target = '_blank';

                var img = document.createElement('img');
                img.src = url;
                img.alt = 'Instagram image';
                img.className = 'w-100';

                link.appendChild(img);
                col.appendChild(link);
                container.appendChild(col);
            });
        })
        .catch(function (error) {
            console.error('Instagram fetch failed', error);
        });
});
