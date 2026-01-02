<?php
$page = $_GET['page'] ?? 'home';

$routes = [
  'home'     => __DIR__ . '/pages/home.php',
  'products' => __DIR__ . '/pages/products.php',
  'gallery'  => __DIR__ . '/pages/gallery.php',
  'about'    => __DIR__ . '/pages/about.php',
  'contact'  => __DIR__ . '/pages/contact.php',
];

$file = $routes[$page] ?? $routes['home'];

$pageTitle = match ($page) {
  'products' => 'Igračke — hej!loop',
  'gallery'  => 'Galerija — hej!loop',
  'about'    => 'O meni — hej!loop',
  'contact'  => 'Kontakt — hej!loop',
  default    => 'hej!loop',
};

require __DIR__ . '/partials/header.php';
require $file;
require __DIR__ . '/partials/footer.php';
