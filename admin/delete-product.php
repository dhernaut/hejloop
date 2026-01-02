<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

require __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header('Location: index.php');
  exit;
}

$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

if ($row && !empty($row['image'])) {
  $path = __DIR__ . '/../assets/img/products/' . $row['image'];
  if (is_file($path)) @unlink($path);
}

header('Location: index.php');
exit;
