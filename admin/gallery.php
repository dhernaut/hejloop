<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

$destDir = __DIR__ . '/../assets/img/gallery/';
$errors = [];
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_FILES['image']['name'])) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'Greška pri uploadu slike.';
    } else {
      $tmp = $_FILES['image']['tmp_name'];
      $size = (int)$_FILES['image']['size'];

      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $mime = $finfo->file($tmp);

      $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
      ];

      if (!isset($allowed[$mime])) {
        $errors[] = 'Dozvoljeni formati: JPG, PNG, WEBP.';
      }
      if ($size > 3_000_000) {
        $errors[] = 'Slika je prevelika (max 3 MB).';
      }

      if (!$errors) {
        if (!is_dir($destDir)) {
          if (!mkdir($destDir, 0755, true)) {
            $errors[] = 'Ne mogu kreirati folder assets/img/gallery/.';
          }
        }

        if (!$errors) {
          $ext = $allowed[$mime];
          $base = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
          $safeBase = preg_replace('/[^a-z0-9]+/i', '-', strtolower($base));
          $safeBase = trim($safeBase, '-');
          $imageFileName = $safeBase . '-' . bin2hex(random_bytes(4)) . '.' . $ext;

          $destPath = $destDir . $imageFileName;
          if (!move_uploaded_file($tmp, $destPath)) {
            $errors[] = 'Ne mogu spremiti sliku.';
          } else {
            $ok = true;
          }
        }
      }
    }
  } else {
    $errors[] = 'Odaberite sliku.';
  }
}

$images = [];
if (is_dir($destDir)) {
  $files = scandir($destDir) ?: [];
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
<!doctype html>
<html lang="hr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — Galerija</title>
  <?php $cssVersion = filemtime(__DIR__ . '/../assets/css/style.css'); ?>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= $cssVersion ?>">
</head>
<body>
<main class="container">
  <div class="header-inner" style="border-bottom:1px solid #eee; padding-bottom:12px; margin-bottom:16px;">
    <strong>Admin — Galerija</strong>
    <div>
      <a class="btn" href="messages.php">Poruke</a>
      <a class="btn" href="index.php" style="margin-left:8px;">Proizvodi</a>
      <a class="btn" href="logout.php" style="margin-left:8px;">Odjava</a>
    </div>
  </div>

  <h1>Galerija</h1>

  <?php if ($ok): ?>
    <p class="notice">Slika je dodana.</p>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="notice">
      <strong>Greške:</strong>
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" style="margin-bottom:18px;">
    <label>Slika (JPG/PNG/WEBP, max 3MB)
      <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
    </label>
    <button class="btn" type="submit">Dodaj u galeriju</button>
  </form>

  <?php if (!$images): ?>
    <p>Trenutno nema slika u galeriji.</p>
  <?php else: ?>
    <div class="gallery-grid admin-gallery">
      <?php foreach ($images as $img): ?>
        <div class="gallery-item">
          <img class="gallery-img" src="../assets/img/gallery/<?= htmlspecialchars($img) ?>" alt="Galerija">
          <form class="gallery-actions" method="post" action="delete-gallery.php" onsubmit="return confirm('Obrisati sliku?');">
            <input type="hidden" name="file" value="<?= htmlspecialchars($img) ?>">
            <button class="btn" type="submit">Obriši</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p style="margin-top:18px;"><a href="../">← Natrag na stranicu</a></p>
</main>
</body>
</html>
