<?php $pageTitle = 'Order Confirmed'; ?>
<div class="confirmation-page">
  <div class="confirmation-card">
    <div class="confirmation-icon">✅</div>
    <h1>Order Confirmed!</h1>
    <p class="confirmation-ref">Reference: <strong><?= htmlspecialchars($order['reference']) ?></strong></p>
    <p>Thank you, <?= htmlspecialchars($order['delivery_name']) ?>. Your order has been received and is being processed.</p>

    <div class="confirmation-details">
      <div class="confirmation-row"><span>Order Reference</span><strong><?= htmlspecialchars($order['reference']) ?></strong></div>
      <div class="confirmation-row"><span>Status</span><span class="badge badge-amber">Pending</span></div>
      <div class="confirmation-row"><span>Delivery To</span><span><?= htmlspecialchars($order['delivery_address']) ?></span></div>
      <div class="confirmation-row"><span>Order Total</span><strong>GH₵<?= number_format($order['total_amount'], 2) ?></strong></div>
    </div>

    <h3>Items Ordered</h3>
    <div class="confirmation-items">
      <?php foreach ($order['items'] as $item): ?>
        <div class="confirmation-item">
          <span><?= htmlspecialchars($item['product_name']) ?></span>
          <span class="badge <?= $item['type'] === 'rent' ? 'badge-blue' : 'badge-green' ?>"><?= ucfirst($item['type']) ?></span>
          <?php if ($item['type'] === 'rent'): ?>
            <span><?= htmlspecialchars($item['rental_start']) ?> → <?= htmlspecialchars($item['rental_end']) ?></span>
          <?php else: ?>
            <span>Qty: <?= $item['quantity'] ?></span>
          <?php endif; ?>
          <strong>GH₵<?= number_format($item['line_total'], 2) ?></strong>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="confirmation-actions">
      <a href="/orders" class="btn btn-primary">View My Orders</a>
      <a href="/catalogue" class="btn btn-outline">Continue Shopping</a>
    </div>
  </div>
</div>
