<?php $pageTitle = 'Manage Products'; ?>
<div class="section-header">
  <h2>All Products (<?= count($products) ?>)</h2>
  <a href="/admin/products/create" class="btn btn-primary">➕ Add New Product</a>
</div>

<table class="admin-table">
  <thead>
    <tr><th>Image</th><th>Name</th><th>Category</th><th>Buy Price</th><th>Rent/Day</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr class="<?= !$p['is_available'] ? 'row-disabled' : '' ?>">
        <td><img src="/images/products/<?= htmlspecialchars($p['image_url']) ?>"
                 onerror="this.src='/images/products/default.jpg'"
                 class="table-thumb" alt=""></td>
        <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
        <td><?= htmlspecialchars($p['category_name']) ?></td>
        <td><?= $p['buy_price']      ? 'GH₵' . number_format($p['buy_price'], 2)      : '—' ?></td>
        <td><?= $p['rent_price_day'] ? 'GH₵' . number_format($p['rent_price_day'], 2) : '—' ?></td>
        <td><?= $p['stock'] ?></td>
        <td><span class="badge <?= $p['is_available'] ? 'badge-green' : 'badge-red' ?>">
          <?= $p['is_available'] ? 'Active' : 'Hidden' ?>
        </span></td>
        <td class="action-cell">
          <a href="/admin/products/<?= $p['id'] ?>/edit" class="btn btn-sm">Edit</a>
          <!-- Quick price update -->
          <button class="btn btn-sm btn-outline" onclick="togglePriceForm(<?= $p['id'] ?>)">Prices</button>
          <form method="POST" action="/admin/products/<?= $p['id'] ?>/delete"
                onsubmit="return confirm('Remove this product from the catalogue?')">
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      <!-- Inline price form -->
      <tr id="price-form-<?= $p['id'] ?>" class="price-row hidden">
        <td colspan="8">
          <form method="POST" action="/admin/products/<?= $p['id'] ?>/prices" class="inline-price-form">
            <label>Buy Price: <input type="number" name="buy_price" step="0.01"
                   value="<?= $p['buy_price'] ?? '' ?>" placeholder="0.00"></label>
            <label>Rent/Day: <input type="number" name="rent_price_day" step="0.01"
                   value="<?= $p['rent_price_day'] ?? '' ?>" placeholder="0.00"></label>
            <button type="submit" class="btn btn-primary btn-sm">Update Prices</button>
            <button type="button" class="btn btn-outline btn-sm"
                    onclick="togglePriceForm(<?= $p['id'] ?>)">Cancel</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
