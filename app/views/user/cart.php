<?php $pageTitle = 'My Cart'; ?>
<div class="page-header">
  <h1>🛒 My Cart</h1>
</div>

<?php if (empty($cart)): ?>
  <div class="empty-state">
    <div class="empty-icon">🛒</div>
    <h2>Your cart is empty</h2>
    <p>Browse our equipment catalogue to get started.</p>
    <a href="/catalogue" class="btn btn-primary">Browse Equipment</a>
  </div>
<?php else: ?>
  <div class="cart-layout">
    <div class="cart-items">
      <?php foreach ($cart as $index => $item): ?>
        <div class="cart-item">
          <img src="/images/products/<?= htmlspecialchars($item['image_url']) ?>"
               alt="<?= htmlspecialchars($item['product_name']) ?>"
               onerror="this.src='/images/products/default.jpg'"
               class="cart-item-image">
          <div class="cart-item-info">
            <h3><?= htmlspecialchars($item['product_name']) ?></h3>
            <span class="cart-badge <?= $item['type'] === 'rent' ? 'badge-blue' : 'badge-green' ?>">
              <?= $item['type'] === 'rent' ? '📅 Rental' : '🛒 Purchase' ?>
            </span>
            <?php if ($item['type'] === 'rent'): ?>
              <p class="cart-meta">
                <?= htmlspecialchars($item['rental_start']) ?> → <?= htmlspecialchars($item['rental_end']) ?>
                (<?= $item['rental_days'] ?> day<?= $item['rental_days'] > 1 ? 's' : '' ?>)
              </p>
            <?php endif; ?>
            <p class="cart-meta">Qty: <?= $item['quantity'] ?> × GH₵<?= number_format($item['unit_price'], 2) ?></p>
          </div>
          <div class="cart-item-total">
            <strong>GH₵<?= number_format($item['line_total'], 2) ?></strong>
            <form method="POST" action="/cart/remove">
              <input type="hidden" name="index" value="<?= $index ?>">
              <button type="submit" class="btn-remove">✕ Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cart-summary">
      <h2>Order Summary</h2>
      <div class="summary-row"><span>Items (<?= count($cart) ?>)</span><span>GH₵<?= number_format($total, 2) ?></span></div>
      <div class="summary-row"><span>Delivery</span><span>Arranged on confirmation</span></div>
      <div class="summary-total"><span>Total</span><span>GH₵<?= number_format($total, 2) ?></span></div>
      <a href="/checkout" class="btn btn-primary btn-full btn-large">Proceed to Checkout →</a>
      <form method="POST" action="/cart/clear" style="margin-top:10px">
        <button type="submit" class="btn btn-outline btn-full">Clear Cart</button>
      </form>
      <a href="/catalogue" class="btn btn-link btn-full">← Continue Shopping</a>
    </div>
  </div>
<?php endif; ?>
