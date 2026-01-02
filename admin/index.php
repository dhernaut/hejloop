<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

require __DIR__ . '/../config/db.php';

$stmt = $pdo->query("SELECT id, name, price, image, created_at FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="hr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — hej!loop</title>
  <?php $cssVersion = filemtime(__DIR__ . '/../assets/css/style.css'); ?>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= $cssVersion ?>">
</head>
<body>
<main class="container">
  <div class="header-inner" style="border-bottom:1px solid #eee; padding-bottom:12px; margin-bottom:16px;">
    <strong>Admin — hej!loop</strong>
    <div>
      <a class="btn" href="messages.php">Poruke</a>
      <a class="btn" href="gallery.php" style="margin-left:8px;">Galerija</a>
      <a class="btn" href="add-product.php">+ Dodaj proizvod</a>
      <a class="btn" href="logout.php" style="margin-left:8px;">Odjava</a>
    </div>
  </div>

  <h1>Proizvodi</h1>

  <?php if (!$products): ?>
    <p>Nema proizvoda.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($products as $p): ?>
        <article class="card">
          <?php if (!empty($p['image'])): ?>
            <img class="product-img" src="../assets/img/products/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
          <?php endif; ?>
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <p><strong><?= number_format((float)$p['price'], 2, ',', '.') ?> €</strong></p>

          <p style="margin-top:10px;">
            <a href="delete-product.php?id=<?= (int)$p['id'] ?>" onclick="return confirm('Obrisati proizvod?');">Obriši</a>
          </p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p style="margin-top:18px;"><a href="../">← Natrag na stranicu</a></p>
</main>
</body>
</html>
