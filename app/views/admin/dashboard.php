<?php $pageTitle = 'Dashboard'; ?>
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">👥</div>
    <div class="stat-value"><?= $totalUsers ?></div>
    <div class="stat-label">Registered Users</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">🔧</div>
    <div class="stat-value"><?= $totalProducts ?></div>
    <div class="stat-label">Products Listed</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">📦</div>
    <div class="stat-value"><?= $totalOrders ?></div>
    <div class="stat-label">Total Orders</div>
  </div>
  <div class="stat-card stat-highlight">
    <div class="stat-icon">💰</div>
    <div class="stat-value">GH₵<?= number_format($totalRevenue, 0) ?></div>
    <div class="stat-label">Total Revenue</div>
  </div>
</div>

<?php if ($pendingOrders > 0): ?>
  <div class="alert alert-warning">
    ⚠️ You have <strong><?= $pendingOrders ?></strong> pending order<?= $pendingOrders > 1 ? 's' : '' ?> awaiting confirmation.
    <a href="/admin/orders?status=pending">View Pending Orders →</a>
  </div>
<?php endif; ?>

<div class="admin-section">
  <div class="section-header">
    <h2>Recent Orders</h2>
    <a href="/admin/orders" class="btn btn-outline">View All</a>
  </div>
  <table class="admin-table">
    <thead>
      <tr><th>Reference</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($recentOrders as $order): ?>
        <tr>
          <td><strong><?= htmlspecialchars($order['reference']) ?></strong></td>
          <td><?= htmlspecialchars($order['user_name']) ?></td>
          <td>GH₵<?= number_format($order['total_amount'], 2) ?></td>
          <td><span class="badge badge-<?= match($order['status']) {
            'confirmed'=>'green','fulfilled'=>'green','pending'=>'amber','cancelled'=>'red','returned'=>'blue',default=>'grey'
          } ?>"><?= ucfirst($order['status']) ?></span></td>
          <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
          <td><a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-sm">View</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="admin-quick-links">
  <a href="/admin/products/create" class="quick-link">➕ Add New Product</a>
  <a href="/admin/orders?status=pending" class="quick-link">📋 Pending Orders</a>
  <a href="/admin/products" class="quick-link">🔧 Manage Products</a>
</div>
