<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: gallery.php');
  exit;
}

$file = $_POST['file'] ?? '';
$file = basename($file);

if (!preg_match('/\.(jpe?g|png|webp)$/i', $file)) {
  header('Location: gallery.php');
  exit;
}

$path = __DIR__ . '/../assets/img/gallery/' . $file;
if (is_file($path)) {
  unlink($path);
}

header('Location: gallery.php');
exit;
