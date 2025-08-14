const INSTAGRAM_USER = 'ingoskleinekaeltehilfe';
const MAX_PHOTOS = 9;

async function loadInstagramFeed() {
  const container = document.getElementById('instagram-feed');
  if (!container) return;
  try {
    const response = await fetch(`https://www.instagram.com/${INSTAGRAM_USER}/?__a=1&__d=dis`);
    const json = await response.json();
    const edges = json.graphql.user.edge_owner_to_timeline_media.edges.slice(0, MAX_PHOTOS);
    edges.forEach(({ node }) => {
      const imgUrl = node.thumbnail_src;
      const link = `https://www.instagram.com/p/${node.shortcode}/`;
      const col = document.createElement('div');
      col.className = 'col-4 col-md-3 mb-3';
      const a = document.createElement('a');
      a.href = link;
      a.target = '_blank';
      const img = document.createElement('img');
      img.src = imgUrl;
      img.alt = node.accessibility_caption || 'Instagram image';
      img.className = 'img-fluid';
      a.appendChild(img);
      col.appendChild(a);
      container.appendChild(col);
    });
  } catch (e) {
    console.error('Instagram feed error', e);
  }
}

if (document.readyState !== 'loading') {
  loadInstagramFeed();
} else {
  document.addEventListener('DOMContentLoaded', loadInstagramFeed);
}
