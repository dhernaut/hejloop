<?php
require __DIR__ . '/../config/db.php';

$stmt = $pdo->query("SELECT name, description, price, image FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<h1>Igračke</h1>

<?php if (!$products): ?>
  <p>Trenutno nema proizvoda.</p>
<?php else: ?>
  <div class="grid">
    <?php foreach ($products as $p): ?>
      <article class="card">
        <?php if (!empty($p['image'])): ?>
          <img class="product-img" src="assets/img/products/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" data-full="assets/img/products/<?= htmlspecialchars($p['image']) ?>">
        <?php endif; ?>
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p><?= htmlspecialchars($p['description']) ?></p>
        <p><strong><?= number_format((float)$p['price'], 2, ',', '.') ?> €</strong></p>
      </article>
    <?php endforeach; ?>
  </div>
  <div class="lightbox" id="lightbox" aria-hidden="true">
    <button class="lightbox-close" type="button" aria-label="Zatvori">×</button>
    <img class="lightbox-img" src="" alt="">
  </div>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var images = document.querySelectorAll('.product-img');
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
        var full = img.getAttribute('data-full') || img.src;
        lightboxImg.src = full;
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
