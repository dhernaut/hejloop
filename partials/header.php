<?php $cssVersion = filemtime(__DIR__ . '/../assets/css/style.css'); ?>
<!doctype html>
<html lang="hr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'hej!loop') ?></title>
  <link rel="stylesheet" href="assets/css/style.css?v=<?= $cssVersion ?>">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="./">hej!loop</a>
    <nav class="nav">
      <a href="./?page=products">Igračke</a>
      <a href="./?page=gallery">Galerija</a>
      <a href="./?page=about">O meni</a>
      <a href="./?page=contact">Kontakt</a>
    </nav>
  </div>
</header>
<main class="container">

