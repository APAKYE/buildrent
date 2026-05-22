<?php
$pageTitle = $pageTitle ?? 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> | BuildRent Admin</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

<div class="admin-wrapper">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">🏗️ BuildRent<span>Admin</span></div>
    <nav class="sidebar-nav">
      <a href="/admin/dashboard"  class="sidebar-link">📊 Dashboard</a>
      <a href="/admin/products"   class="sidebar-link">🔧 Products</a>
      <a href="/admin/orders"     class="sidebar-link">📦 Orders</a>
      <hr class="sidebar-divider">
      <a href="/catalogue"        class="sidebar-link">🌐 View Site</a>
      <a href="/logout"           class="sidebar-link sidebar-logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="admin-main">
    <header class="admin-header">
      <div class="admin-header-title"><?= htmlspecialchars($pageTitle) ?></div>
      <div class="admin-header-user">👤 <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
    </header>

    <div class="admin-content">

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?><?php unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?><?php unset($_SESSION['error']); ?></div>
    <?php endif; ?>
