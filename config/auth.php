<?php
declare(strict_types=1);
session_start();

function require_admin(): void {
  if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
  }
}
