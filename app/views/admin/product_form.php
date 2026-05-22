<?php
$editing   = isset($product);
$pageTitle = $editing ? 'Edit Product' : 'Add New Product';
?>
<div class="section-header">
  <h2><?= $editing ? 'Edit: ' . htmlspecialchars($product['name']) : 'Add New Product' ?></h2>
  <a href="/admin/products" class="btn btn-outline">← Back to Products</a>
</div>

<form method="POST"
      action="<?= $editing ? '/admin/products/' . $product['id'] . '/update' : '/admin/products/store' ?>"
      class="admin-form">

  <div class="form-grid">
    <div class="form-group">
      <label for="name">Product Name *</label>
      <input type="text" id="name" name="name" required
             value="<?= htmlspecialchars($product['name'] ?? '') ?>" placeholder="e.g. Belle Minimix 150 Concrete Mixer">
    </div>

    <div class="form-group">
      <label for="category_id">Category *</label>
      <select id="category_id" name="category_id" required>
        <option value="">Select category...</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"
            <?= ($product['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="buy_price">Purchase Price (GH₵)</label>
      <input type="number" id="buy_price" name="buy_price" step="0.01" min="0"
             value="<?= htmlspecialchars($product['buy_price'] ?? '') ?>" placeholder="Leave blank if not for sale">
    </div>

    <div class="form-group">
      <label for="rent_price_day">Rental Price per Day (GH₵)</label>
      <input type="number" id="rent_price_day" name="rent_price_day" step="0.01" min="0"
             value="<?= htmlspecialchars($product['rent_price_day'] ?? '') ?>" placeholder="Leave blank if not for rent">
    </div>

    <div class="form-group">
      <label for="stock">Stock / Units Available *</label>
      <input type="number" id="stock" name="stock" min="0" required
             value="<?= htmlspecialchars($product['stock'] ?? '0') ?>">
    </div>

    <div class="form-group">
      <label for="image_url">Image Filename</label>
      <input type="text" id="image_url" name="image_url"
             value="<?= htmlspecialchars($product['image_url'] ?? 'default.jpg') ?>"
             placeholder="e.g. concrete-mixer.jpg">
      <small>Place image files in <code>/public/images/products/</code></small>
    </div>

    <?php if ($editing): ?>
    <div class="form-group">
      <label>
        <input type="checkbox" name="is_available" value="1"
               <?= ($product['is_available'] ?? 1) ? 'checked' : '' ?>>
        Product is active and visible in catalogue
      </label>
    </div>
    <?php endif; ?>
  </div>

  <div class="form-group form-group-full">
    <label for="description">Description *</label>
    <textarea id="description" name="description" rows="5" required
              placeholder="Detailed product description, specifications, and use cases..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">
      <?= $editing ? '💾 Save Changes' : '➕ Add Product' ?>
    </button>
    <a href="/admin/products" class="btn btn-outline">Cancel</a>
  </div>
</form>
