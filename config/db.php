<?php
declare(strict_types=1);

$DB_HOST = 'localhost';
$DB_PORT = '8889';
$DB_NAME = 'hejloop_db';
$DB_USER = 'root';
$DB_PASS = 'root';

$pdo = new PDO(
  "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4",
  $DB_USER,
  $DB_PASS,
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]
);
