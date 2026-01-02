<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

require __DIR__ . '/../config/db.php';

$stmt = $pdo->query('SELECT id, name, email, message, mail_sent, mail_status, created_at FROM contact_messages ORDER BY created_at DESC');
$messages = $stmt->fetchAll();
?>
<!doctype html>
<html lang="hr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — Poruke</title>
  <?php $cssVersion = filemtime(__DIR__ . '/../assets/css/style.css'); ?>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?= $cssVersion ?>">
</head>
<body>
<main class="container">
  <div class="header-inner" style="border-bottom:1px solid #eee; padding-bottom:12px; margin-bottom:16px;">
    <strong>Admin — Poruke</strong>
    <div>
      <a class="btn" href="messages-export.php">CSV Export</a>
      <a class="btn" href="index.php" style="margin-left:8px;">Proizvodi</a>
      <a class="btn" href="logout.php" style="margin-left:8px;">Odjava</a>
    </div>
  </div>

  <h1>Kontakt poruke</h1>

  <?php if (!$messages): ?>
    <p>Nema poruka.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Datum</th>
          <th>Ime</th>
          <th>Email</th>
          <th>Mail</th>
          <th>Poruka</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $m): ?>
          <tr>
            <td><?= htmlspecialchars($m['created_at']) ?></td>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
            <td><?= !empty($m['mail_sent']) ? 'Poslano' : 'Neuspjelo' ?></td>
            <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <p style="margin-top:18px;"><a href="../">← Natrag na stranicu</a></p>
</main>
</body>
</html>
