<?php $pageTitle = 'Order ' . $order['reference']; ?>
<div class="section-header">
  <h2>Order: <?= htmlspecialchars($order['reference']) ?></h2>
  <a href="/admin/orders" class="btn btn-outline">← All Orders</a>
</div>

<div class="order-detail-grid">
  <div class="order-detail-info">
    <div class="detail-card">
      <h3>Customer</h3>
      <p><strong><?= htmlspecialchars($order['user_name']) ?></strong></p>
      <p><?= htmlspecialchars($order['user_email']) ?></p>
    </div>
    <div class="detail-card">
      <h3>Delivery</h3>
      <p><?= htmlspecialchars($order['delivery_name']) ?></p>
      <p><?= nl2br(htmlspecialchars($order['delivery_address'])) ?></p>
      <?php if ($order['notes']): ?>
        <p><em><?= htmlspecialchars($order['notes']) ?></em></p>
      <?php endif; ?>
    </div>
    <div class="detail-card">
      <h3>Order Info</h3>
      <p>Date: <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
      <p>Total: <strong>GH₵<?= number_format($order['total_amount'], 2) ?></strong></p>
      <p>Status: <span class="badge badge-<?= match($order['status']) {
        'confirmed'=>'green','fulfilled'=>'green','pending'=>'amber','cancelled'=>'red','returned'=>'blue',default=>'grey'
      } ?>"><?= ucfirst($order['status']) ?></span></p>
    </div>
  </div>

  <div class="order-detail-actions">
    <h3>Update Status</h3>
    <form method="POST" action="/admin/orders/<?= $order['id'] ?>/status">
      <select name="status" class="filter-select">
        <?php foreach (['pending','confirmed','fulfilled','cancelled','returned'] as $s): ?>
          <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
  </div>
</div>

<h3>Order Items</h3>
<table class="admin-table">
  <thead>
    <tr><th>Product</th><th>Type</th><th>Qty</th><th>Unit Price</th><th>Rental Period</th><th>Line Total</th></tr>
  </thead>
  <tbody>
    <?php foreach ($order['items'] as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><span class="badge <?= $item['type']==='rent' ? 'badge-blue' : 'badge-green' ?>"><?= ucfirst($item['type']) ?></span></td>
        <td><?= $item['quantity'] ?></td>
        <td>GH₵<?= number_format($item['unit_price'], 2) ?></td>
        <td><?= $item['rental_start'] ? $item['rental_start'] . ' → ' . $item['rental_end'] . ' (' . $item['rental_days'] . ' days)' : '—' ?></td>
        <td><strong>GH₵<?= number_format($item['line_total'], 2) ?></strong></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
