<?php $pageTitle = 'My Orders'; ?>
<div class="page-header">
  <h1>My Orders</h1>
</div>

<?php if (empty($orders)): ?>
  <div class="empty-state">
    <div class="empty-icon">📦</div>
    <h2>No orders yet</h2>
    <p>Your order history will appear here once you place an order.</p>
    <a href="/catalogue" class="btn btn-primary">Browse Equipment</a>
  </div>
<?php else: ?>
  <div class="orders-list">
    <?php foreach ($orders as $order): ?>
      <div class="order-card">
        <div class="order-card-header">
          <div>
            <span class="order-ref"><?= htmlspecialchars($order['reference']) ?></span>
            <span class="order-date"><?= date('d M Y', strtotime($order['created_at'])) ?></span>
          </div>
          <div>
            <span class="badge badge-<?= match($order['status']) {
              'confirmed'  => 'green',
              'fulfilled'  => 'green',
              'pending'    => 'amber',
              'cancelled'  => 'red',
              'returned'   => 'blue',
              default      => 'grey'
            } ?>"><?= ucfirst($order['status']) ?></span>
            <strong class="order-total">GH₵<?= number_format($order['total_amount'], 2) ?></strong>
          </div>
        </div>
        <div class="order-card-footer">
          <span>Deliver to: <?= htmlspecialchars($order['delivery_address']) ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
