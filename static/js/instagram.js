document.addEventListener('DOMContentLoaded', function () {
  fetch('/instagram.php')
    .then(function (response) { return response.json(); })
    .then(function (images) {
      var container = document.getElementById('instagram-feed');
      if (!container) return;
      images.forEach(function (url) {
        var col = document.createElement('div');
        col.className = 'col-4 col-md-3 col-lg-2 mb-2';
        var img = document.createElement('img');
        img.src = url;
        img.className = 'img-fluid rounded';
        col.appendChild(img);
        container.appendChild(col);
      });
    })
    .catch(function (err) {
      console.error('Instagram fetch error', err);
    });
});
