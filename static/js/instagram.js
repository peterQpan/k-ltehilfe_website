document.addEventListener('DOMContentLoaded', function () {
  var container = document.getElementById('instagram-feed');
  if (!container) return;
  fetch('/instagram-feed.php')
    .then(function (resp) { return resp.json(); })
    .then(function (items) {
      if (!Array.isArray(items)) return;
      items.forEach(function (item) {
        if (!item.thumbnail) return;
        var a = document.createElement('a');
        a.href = item.url;
        a.target = '_blank';
        var img = document.createElement('img');
        img.src = item.thumbnail;
        img.alt = 'Instagram Bild';
        a.appendChild(img);
        container.appendChild(a);
      });
    })
    .catch(function (err) {
      console.error('Instagram feed error', err);
    });
});
