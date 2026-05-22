<?php $pageTitle = 'Manage Orders'; ?>
<div class="section-header">
  <h2>All Orders</h2>
  <div class="filter-tabs">
    <?php foreach ([''=>'All','pending'=>'Pending','confirmed'=>'Confirmed','fulfilled'=>'Fulfilled','cancelled'=>'Cancelled'] as $val=>$label): ?>
      <a href="/admin/orders<?= $val ? '?status='.$val : '' ?>"
         class="filter-tab <?= ($_GET['status'] ?? '') === $val ? 'active' : '' ?>"><?= $label ?></a>
    <?php endforeach; ?>
  </div>
</div>

<?php if (empty($orders)): ?>
  <div class="empty-state"><p>No orders found.</p></div>
<?php else: ?>
  <table class="admin-table">
    <thead>
      <tr><th>Reference</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
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
<?php endif; ?>
