<?php $pageTitle = 'Browse Equipment'; ?>
<div class="page-header">
  <h1>Browse Equipment</h1>
  <p>Find the right tool for your project — rent or buy</p>
</div>

<!-- Search & Filter Bar -->
<div class="filter-bar">
  <form method="GET" action="/catalogue" class="filter-form">
    <input type="text" name="search" placeholder="Search equipment..."
           value="<?= htmlspecialchars($search) ?>" class="filter-search">
    <select name="category" class="filter-select">
      <option value="">All Categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary">Search</button>
    <?php if ($search || $categoryId): ?>
      <a href="/catalogue" class="btn btn-outline">Clear</a>
    <?php endif; ?>
  </form>
  <p class="result-count"><?= $total ?> item<?= $total !== 1 ? 's' : '' ?> found</p>
</div>

<!-- Product Grid -->
<?php if (empty($products)): ?>
  <div class="empty-state">
    <div class="empty-icon">🔍</div>
    <h2>No equipment found</h2>
    <p>Try a different search term or category.</p>
    <a href="/catalogue" class="btn btn-primary">Browse All</a>
  </div>
<?php else: ?>
  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <div class="product-card">
        <div class="product-image-wrap">
          <img src="/images/products/<?= htmlspecialchars($product['image_url']) ?>"
               alt="<?= htmlspecialchars($product['name']) ?>"
               onerror="this.src='/images/products/default.jpg'">
          <span class="product-category"><?= htmlspecialchars($product['category_name']) ?></span>
        </div>
        <div class="product-info">
          <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
          <p class="product-desc"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
          <div class="product-pricing">
            <?php if ($product['buy_price']): ?>
              <span class="price-buy">Buy: GH₵<?= number_format($product['buy_price'], 2) ?></span>
            <?php endif; ?>
            <?php if ($product['rent_price_day']): ?>
              <span class="price-rent">Rent: GH₵<?= number_format($product['rent_price_day'], 2) ?>/day</span>
            <?php endif; ?>
          </div>
          <div class="product-stock">
            <?php if ($product['stock'] > 0): ?>
              <span class="badge badge-green">✓ <?= $product['stock'] ?> available</span>
            <?php else: ?>
              <span class="badge badge-red">Out of stock</span>
            <?php endif; ?>
          </div>
          <a href="/product/<?= $product['id'] ?>" class="btn btn-primary btn-full">View Details</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= $categoryId ?>"
           class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
