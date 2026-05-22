<?php
// app/views/shared/layout_top.php
$pageTitle = $pageTitle ?? 'BuildRent';
$cartCount = count($_SESSION['cart'] ?? []);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> | BuildRent</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
  <div class="nav-container">
    <a href="/" class="nav-brand">🏗️ BuildRent</a>
    <div class="nav-links">
      <a href="/catalogue">Browse Equipment</a>
      <a href="/orders">My Orders</a>
      <a href="/cart" class="cart-link">
        🛒 Cart <?php if ($cartCount > 0): ?><span class="cart-badge"><?= $cartCount ?></span><?php endif; ?>
      </a>
      <a href="/account">Account</a>
      <a href="/logout" class="btn-logout">Logout</a>
    </div>
  </div>
</nav>

<main class="main-content">

<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?><?php unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?><?php unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['errors'])): ?>
  <div class="alert alert-error">
    <ul><?php foreach ($_SESSION['errors'] as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>
