<?php
declare(strict_types=1);
session_start();

// testni admin korisnik
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'hej!loop2025';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';

  if (hash_equals(ADMIN_USER, $u) && hash_equals(ADMIN_PASS, $p)) {
    $_SESSION['is_admin'] = true;
    header('Location: index.php');
    exit;
  }
  $error = 'Pogrešan korisnik ili lozinka.';
}
?>
<!doctype html>
<html lang="hr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin login — hej!loop</title>
  <?php $cssVersion = filemtime(__DIR__ . '/../assets/css/style.css'); ?>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= $cssVersion ?>">
</head>
<body>
<main class="container" style="max-width:520px;">
  <h1>Admin login</h1>

  <?php if ($error): ?>
    <p class="notice"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Korisničko ime
      <input type="text" name="username" required>
    </label>
    <label>Lozinka
      <input type="password" name="password" required>
    </label>
    <button class="btn" type="submit">Prijava</button>
  </form>

  <p style="margin-top:12px;"><a href="../">← Natrag na stranicu</a></p>
</main>
</body>
</html>
