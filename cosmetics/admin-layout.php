<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title ?? 'Admin') ?> — GlowBeauty</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="admin.css" />
</head>
<body>
<div class="admin-layout">
  <aside class="admin-sidebar">
    <h1 class="admin-brand">Glow<span>Beauty</span></h1>
    <nav class="admin-nav">
      <a href="admin-dashboard.php" class="<?= $current === 'admin-dashboard.php' ? 'active' : '' ?>">📊 Dashboard</a>
      <a href="admin-products.php"  class="<?= in_array($current, ['admin-products.php','admin-product-edit.php']) ? 'active' : '' ?>">🧴 Products</a>
      <a href="admin-orders.php"    class="<?= in_array($current, ['admin-orders.php','admin-order-view.php'])     ? 'active' : '' ?>">📦 Orders</a>
      <a href="index.php" target="_blank">🌐 View Site</a>
      <a href="admin-logout.php">🚪 Logout</a>
    </nav>
  </aside>

  <main class="admin-main">
    <header class="admin-header">
      <h2><?= e($title ?? 'Dashboard') ?></h2>
      <div class="admin-header__user">
        <span>Signed in as <strong><?= e($_SESSION['admin_user']) ?></strong></span>
      </div>
    </header>