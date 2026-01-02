<?php
$dir = __DIR__ . '/../assets/img/gallery';
$images = [];

if (is_dir($dir)) {
  $files = scandir($dir) ?: [];
  foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
      continue;
    }
    if (!preg_match('/\.(jpe?g|png|webp)$/i', $file)) {
      continue;
    }
    $images[] = $file;
  }
}
?>

<h1>Galerija</h1>

<?php if (!$images): ?>
  <p>Trenutno nema slika u galeriji.</p>
<?php else: ?>
  <div class="gallery-grid">
    <?php foreach ($images as $img): ?>
      <button class="gallery-item" type="button">
        <img class="gallery-img" src="assets/img/gallery/<?= htmlspecialchars($img) ?>" alt="Galerija">
      </button>
    <?php endforeach; ?>
  </div>

  <div class="lightbox" id="lightbox" aria-hidden="true">
    <button class="lightbox-close" type="button" aria-label="Zatvori">×</button>
    <img class="lightbox-img" src="" alt="">
  </div>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var images = document.querySelectorAll('.gallery-img');
    var lightbox = document.getElementById('lightbox');
    if (!lightbox || images.length === 0) return;

    var lightboxImg = lightbox.querySelector('.lightbox-img');
    var closeBtn = lightbox.querySelector('.lightbox-close');

    function closeLightbox() {
      lightbox.classList.remove('is-open');
      lightbox.setAttribute('aria-hidden', 'true');
      lightboxImg.src = '';
      lightboxImg.alt = '';
    }

    images.forEach(function (img) {
      img.addEventListener('click', function () {
        lightboxImg.src = img.src;
        lightboxImg.alt = img.alt || '';
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');
      });
    });

    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) {
        closeLightbox();
      }
    });
    closeBtn.addEventListener('click', closeLightbox);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeLightbox();
    });
  });
</script>
