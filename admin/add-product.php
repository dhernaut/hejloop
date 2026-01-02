<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

require __DIR__ . '/../config/db.php';

$errors = [];
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);

  if ($name === '') $errors[] = 'Naziv je obavezan.';
  if ($description === '') $errors[] = 'Opis je obavezan.';
  if ($price < 0) $errors[] = 'Cijena ne može biti negativna.';

  // Upload slike (opcionalno)
  $imageFileName = null;
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
        $ext = $allowed[$mime];
        $safeBase = preg_replace('/[^a-z0-9]+/i', '-', strtolower($name));
        $safeBase = trim($safeBase, '-');
        $imageFileName = $safeBase . '-' . bin2hex(random_bytes(4)) . '.' . $ext;

        $destDir = __DIR__ . '/../assets/img/products/';
        if (!is_dir($destDir)) {
          $errors[] = 'Ne postoji folder assets/img/products/.';
        } else {
          $destPath = $destDir . $imageFileName;
          if (!move_uploaded_file($tmp, $destPath)) {
            $errors[] = 'Ne mogu spremiti sliku.';
          }
        }
      }
    }
  }

  if (!$errors) {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $imageFileName]);
    $ok = true;
  }
}
?>
<!doctype html>
<html lang="hr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dodaj proizvod — admin</title>
  <?php $cssVersion = filemtime(__DIR__ . '/../assets/css/style.css'); ?>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= $cssVersion ?>">
</head>
<body>
<main class="container" style="max-width:760px;">
  <h1>Dodaj proizvod</h1>

  <?php if ($ok): ?>
    <p class="notice">Proizvod je dodan. <a href="index.php">Povratak na listu</a></p>
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

  <form method="post" enctype="multipart/form-data">
    <label>Naziv
      <input type="text" name="name" required>
    </label>

    <label>Opis
      <textarea name="description" rows="5" required></textarea>
    </label>

    <label>Cijena (€)
      <input type="number" name="price" step="0.01" min="0" value="0.00">
    </label>

    <label>Slika (JPG/PNG/WEBP, max 3MB)
      <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
    </label>

    <div class="form-actions">
      <button class="btn" type="submit">Spremi</button>
      <a class="btn" href="index.php">Odustani</a>
    </div>
  </form>
</main>
</body>
</html>
