<?php $pageTitle = $product['name']; ?>
<div class="product-detail">
  <div class="breadcrumb"><a href="/catalogue">← Back to Catalogue</a></div>

  <div class="detail-grid">
    <!-- Image -->
    <div class="detail-image">
      <img src="/images/products/<?= htmlspecialchars($product['image_url']) ?>"
           alt="<?= htmlspecialchars($product['name']) ?>"
           onerror="this.src='/images/products/default.jpg'">
      <span class="detail-category"><?= htmlspecialchars($product['category_name']) ?></span>
    </div>

    <!-- Info -->
    <div class="detail-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <p class="detail-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

      <div class="detail-pricing">
        <?php if ($product['buy_price']): ?>
          <div class="price-card price-buy-card">
            <span class="price-label">Purchase Price</span>
            <span class="price-amount">GH₵<?= number_format($product['buy_price'], 2) ?></span>
          </div>
        <?php endif; ?>
        <?php if ($product['rent_price_day']): ?>
          <div class="price-card price-rent-card">
            <span class="price-label">Rental Rate</span>
            <span class="price-amount">GH₵<?= number_format($product['rent_price_day'], 2) ?><small>/day</small></span>
          </div>
        <?php endif; ?>
      </div>

      <div class="detail-availability">
        <?php if ($product['stock'] > 0): ?>
          <span class="badge badge-green">✓ <?= $product['stock'] ?> unit<?= $product['stock'] > 1 ? 's' : '' ?> available</span>
        <?php else: ?>
          <span class="badge badge-red">Currently out of stock</span>
        <?php endif; ?>
      </div>

      <?php if ($product['stock'] > 0): ?>

        <!-- Mode Tabs -->
        <div class="mode-tabs">
          <?php if ($product['buy_price']): ?>
            <button class="mode-tab active" onclick="switchMode('buy')">🛒 Buy</button>
          <?php endif; ?>
          <?php if ($product['rent_price_day']): ?>
            <button class="mode-tab <?= !$product['buy_price'] ? 'active' : '' ?>" onclick="switchMode('rent')">📅 Rent</button>
          <?php endif; ?>
        </div>

        <!-- Buy Form -->
        <?php if ($product['buy_price']): ?>
        <form method="POST" action="/cart/add" id="form-buy" class="order-form">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <input type="hidden" name="type" value="buy">
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="qty-input">
          </div>
          <div class="form-group">
            <p class="total-preview">Total: GH₵<span id="buy-total"><?= number_format($product['buy_price'], 2) ?></span></p>
          </div>
          <button type="submit" class="btn btn-primary btn-full btn-large">Add to Cart — Buy</button>
        </form>
        <?php endif; ?>

        <!-- Rent Form -->
        <?php if ($product['rent_price_day']): ?>
        <form method="POST" action="/cart/add" id="form-rent"
              class="order-form <?= $product['buy_price'] ? 'hidden' : '' ?>">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <input type="hidden" name="type" value="rent">
          <div class="form-row">
            <div class="form-group">
              <label>Rental Start Date</label>
              <input type="date" name="rental_start" id="rental-start" required
                     min="<?= date('Y-m-d') ?>" onchange="calcRentalTotal()">
            </div>
            <div class="form-group">
              <label>Rental End Date</label>
              <input type="date" name="rental_end" id="rental-end" required
                     min="<?= date('Y-m-d', strtotime('+1 day')) ?>" onchange="calcRentalTotal()">
            </div>
          </div>
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>"
                   class="qty-input" onchange="calcRentalTotal()">
          </div>
          <div class="rental-summary" id="rental-summary" style="display:none;">
            <p>📅 <span id="rental-days">0</span> day(s) × GH₵<?= number_format($product['rent_price_day'], 2) ?>/day</p>
            <p class="total-preview">Total: GH₵<span id="rent-total">0.00</span></p>
          </div>
          <button type="submit" class="btn btn-secondary btn-full btn-large">Add to Cart — Rent</button>
        </form>
        <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>
</div>

<script>
const rentPriceDay = <?= (float)($product['rent_price_day'] ?? 0) ?>;

function switchMode(mode) {
  document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
  event.target.classList.add('active');
  document.querySelectorAll('.order-form').forEach(f => f.classList.add('hidden'));
  document.getElementById('form-' + mode).classList.remove('hidden');
}

function calcRentalTotal() {
  const start = new Date(document.getElementById('rental-start').value);
  const end   = new Date(document.getElementById('rental-end').value);
  const qty   = parseInt(document.querySelector('#form-rent [name=quantity]').value) || 1;
  if (start && end && end > start) {
    const days  = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    const total = (days * rentPriceDay * qty).toFixed(2);
    document.getElementById('rental-days').textContent  = days;
    document.getElementById('rent-total').textContent   = total;
    document.getElementById('rental-summary').style.display = 'block';
  }
}

// Update buy total on quantity change
document.querySelector('#form-buy .qty-input')?.addEventListener('input', function() {
  const buyPrice = <?= (float)($product['buy_price'] ?? 0) ?>;
  document.getElementById('buy-total').textContent = (this.value * buyPrice).toFixed(2);
});
</script>
