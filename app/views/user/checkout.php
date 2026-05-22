<?php $pageTitle = 'Checkout'; ?>
<div class="page-header">
  <h1>Checkout</h1>
</div>

<div class="checkout-layout">
  <!-- Order Review -->
  <div class="checkout-items">
    <h2>Order Review</h2>
    <?php foreach ($cart as $item): ?>
      <div class="checkout-item">
        <span class="checkout-item-name"><?= htmlspecialchars($item['product_name']) ?></span>
        <span class="checkout-item-type badge <?= $item['type'] === 'rent' ? 'badge-blue' : 'badge-green' ?>">
          <?= ucfirst($item['type']) ?>
        </span>
        <?php if ($item['type'] === 'rent'): ?>
          <span class="checkout-item-meta"><?= $item['rental_days'] ?> days</span>
        <?php else: ?>
          <span class="checkout-item-meta">Qty: <?= $item['quantity'] ?></span>
        <?php endif; ?>
        <span class="checkout-item-price">GH₵<?= number_format($item['line_total'], 2) ?></span>
      </div>
    <?php endforeach; ?>
    <div class="checkout-total">
      <strong>Total: GH₵<?= number_format($total, 2) ?></strong>
    </div>
  </div>

  <!-- Delivery Details Form -->
  <div class="checkout-form-wrap">
    <h2>Delivery Details</h2>
    <form method="POST" action="/checkout/place" class="checkout-form">
      <div class="form-group">
        <label for="delivery_name">Full Name *</label>
        <input type="text" id="delivery_name" name="delivery_name" required
               value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="delivery_address">Delivery Address *</label>
        <textarea id="delivery_address" name="delivery_address" rows="3" required
                  placeholder="Street address, city, region"></textarea>
      </div>
      <div class="form-group">
        <label for="notes">Additional Notes (optional)</label>
        <textarea id="notes" name="notes" rows="2"
                  placeholder="e.g. Deliver before 8am, call on arrival"></textarea>
      </div>

      <div class="checkout-terms">
        <p>By placing your order you agree to our terms and conditions. Rental equipment must be returned in good condition by the agreed end date.</p>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-large">
        Place Order — GH₵<?= number_format($total, 2) ?>
      </button>
      <a href="/cart" class="btn btn-outline btn-full">← Back to Cart</a>
    </form>
  </div>
</div>
