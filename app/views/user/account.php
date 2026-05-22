<?php $pageTitle = 'My Account'; ?>
<div class="page-header">
  <h1>My Account</h1>
</div>
<div class="auth-card" style="max-width:500px">
  <h2 style="margin-bottom:16px">Profile Details</h2>
  <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></p>
  <p style="margin-top:8px"><strong>Role:</strong> <?= htmlspecialchars($_SESSION['user_role'] ?? '') ?></p>
  <div style="margin-top:24px;display:flex;gap:12px">
    <a href="/orders" class="btn btn-primary">View My Orders</a>
    <a href="/catalogue" class="btn btn-outline">Browse Equipment</a>
  </div>
</div>
