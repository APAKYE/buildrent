<?php
// app/controllers/ProductController.php

require_once APP_ROOT . '/app/models/ProductModel.php';
require_once APP_ROOT . '/app/controllers/AuthController.php';

class ProductController {

    private ProductModel $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    /** GET /catalogue */
    public function catalogue(): void {
        AuthController::requireLogin();
        $search     = trim($_GET['search']   ?? '');
        $categoryId = (int) ($_GET['category'] ?? 0);
        $page       = max(1, (int) ($_GET['page'] ?? 1));

        $products   = $this->productModel->search($search, $categoryId, $page);
        $total      = $this->productModel->countAll($search, $categoryId);
        $totalPages = ceil($total / ITEMS_PER_PAGE);
        $categories = $this->productModel->getCategories();

        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/catalogue.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    /** GET /product/{id} */
    public function detail(int $id): void {
        AuthController::requireLogin();
        $product = $this->productModel->findById($id);
        if (!$product) {
            http_response_code(404);
            require APP_ROOT . '/app/views/shared/404.php';
            return;
        }
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/product_detail.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    // ── Admin ─────────────────────────────────────────────────────────────────

    /** GET /admin/products */
    public function adminList(): void {
        AuthController::requireAdmin();
        $products   = $this->productModel->getAllAdmin();
        $categories = $this->productModel->getCategories();
        require APP_ROOT . '/app/views/shared/admin_layout_top.php';
        require APP_ROOT . '/app/views/admin/products.php';
        require APP_ROOT . '/app/views/shared/admin_layout_bottom.php';
    }

    /** GET /admin/products/create */
    public function adminCreate(): void {
        AuthController::requireAdmin();
        $categories = $this->productModel->getCategories();
        require APP_ROOT . '/app/views/shared/admin_layout_top.php';
        require APP_ROOT . '/app/views/admin/product_form.php';
        require APP_ROOT . '/app/views/shared/admin_layout_bottom.php';
    }

    /** POST /admin/products/store */
    public function adminStore(): void {
        AuthController::requireAdmin();
        $data = [
            'category_id'    => (int) ($_POST['category_id'] ?? 0),
            'name'           => trim($_POST['name'] ?? ''),
            'description'    => trim($_POST['description'] ?? ''),
            'buy_price'      => $_POST['buy_price']      !== '' ? (float) $_POST['buy_price']      : null,
            'rent_price_day' => $_POST['rent_price_day'] !== '' ? (float) $_POST['rent_price_day'] : null,
            'stock'          => (int) ($_POST['stock'] ?? 0),
            'image_url'      => trim($_POST['image_url'] ?? 'default.jpg'),
        ];
        if (!$data['name'] || !$data['category_id']) {
            $_SESSION['error'] = 'Name and category are required.';
            header('Location: /admin/products/create'); exit;
        }
        $this->productModel->create($data);
        $_SESSION['success'] = 'Product created successfully.';
        header('Location: /admin/products'); exit;
    }

    /** GET /admin/products/{id}/edit */
    public function adminEdit(int $id): void {
        AuthController::requireAdmin();
        $product    = $this->productModel->findById($id);
        $categories = $this->productModel->getCategories();
        if (!$product) { header('Location: /admin/products'); exit; }
        require APP_ROOT . '/app/views/shared/admin_layout_top.php';
        require APP_ROOT . '/app/views/admin/product_form.php';
        require APP_ROOT . '/app/views/shared/admin_layout_bottom.php';
    }

    /** POST /admin/products/{id}/update */
    public function adminUpdate(int $id): void {
        AuthController::requireAdmin();
        $data = [
            'category_id'    => (int) ($_POST['category_id'] ?? 0),
            'name'           => trim($_POST['name'] ?? ''),
            'description'    => trim($_POST['description'] ?? ''),
            'buy_price'      => $_POST['buy_price']      !== '' ? (float) $_POST['buy_price']      : null,
            'rent_price_day' => $_POST['rent_price_day'] !== '' ? (float) $_POST['rent_price_day'] : null,
            'stock'          => (int) ($_POST['stock'] ?? 0),
            'image_url'      => trim($_POST['image_url'] ?? 'default.jpg'),
            'is_available'   => isset($_POST['is_available']) ? 1 : 0,
        ];
        $this->productModel->update($id, $data);
        $_SESSION['success'] = 'Product updated successfully.';
        header('Location: /admin/products'); exit;
    }

    /** POST /admin/products/{id}/delete */
    public function adminDelete(int $id): void {
        AuthController::requireAdmin();
        $this->productModel->delete($id);
        $_SESSION['success'] = 'Product removed from catalogue.';
        header('Location: /admin/products'); exit;
    }

    /** POST /admin/products/{id}/prices */
    public function adminPrices(int $id): void {
        AuthController::requireAdmin();
        $buy  = $_POST['buy_price']      !== '' ? (float) $_POST['buy_price']      : null;
        $rent = $_POST['rent_price_day'] !== '' ? (float) $_POST['rent_price_day'] : null;
        $this->productModel->updatePrices($id, $buy, $rent);
        $_SESSION['success'] = 'Prices updated.';
        header('Location: /admin/products'); exit;
    }
}
