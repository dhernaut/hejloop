<?php
declare(strict_types=1);
require __DIR__ . '/../config/auth.php';
require_admin();

require __DIR__ . '/../config/db.php';

$stmt = $pdo->query('SELECT name, email, message, created_at FROM contact_messages ORDER BY created_at DESC');
$messages = $stmt->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=contact-messages.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['Name', 'Email', 'Message', 'Created At']);

foreach ($messages as $m) {
  fputcsv($out, [$m['name'], $m['email'], $m['message'], $m['created_at']]);
}

fclose($out);
exit;
