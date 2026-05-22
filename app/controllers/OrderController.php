<?php
// app/controllers/OrderController.php

require_once APP_ROOT . '/app/models/OrderModel.php';
require_once APP_ROOT . '/app/models/ProductModel.php';
require_once APP_ROOT . '/app/controllers/AuthController.php';

class OrderController {

    private OrderModel   $orderModel;
    private ProductModel $productModel;

    public function __construct() {
        $this->orderModel   = new OrderModel();
        $this->productModel = new ProductModel();
    }

    // ── CART ─────────────────────────────────────────────────────────────────

    /** POST /cart/add */
    public function addToCart(): void {
        AuthController::requireLogin();
        $productId = (int) ($_POST['product_id'] ?? 0);
        $type      = $_POST['type'] ?? 'buy'; // 'buy' or 'rent'
        $qty       = max(1, (int) ($_POST['quantity'] ?? 1));
        $startDate = $_POST['rental_start'] ?? '';
        $endDate   = $_POST['rental_end']   ?? '';

        $product = $this->productModel->findById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Product not found.';
            header('Location: /catalogue'); exit;
        }

        // Calculate price and line total
        if ($type === 'rent') {
            if (!$startDate || !$endDate) {
                $_SESSION['error'] = 'Please select rental start and end dates.';
                header("Location: /product/$productId"); exit;
            }
            $start = new DateTime($startDate);
            $end   = new DateTime($endDate);
            if ($end <= $start) {
                $_SESSION['error'] = 'End date must be after start date.';
                header("Location: /product/$productId"); exit;
            }
            $days       = (int) $start->diff($end)->days;
            $unitPrice  = (float) $product['rent_price_day'];
            $lineTotal  = $unitPrice * $days * $qty;
        } else {
            $unitPrice  = (float) $product['buy_price'];
            $lineTotal  = $unitPrice * $qty;
            $startDate  = null;
            $endDate    = null;
            $days       = null;
        }

        // Build cart item
        $cartItem = [
            'product_id'    => $productId,
            'product_name'  => $product['name'],
            'image_url'     => $product['image_url'],
            'type'          => $type,
            'quantity'      => $qty,
            'unit_price'    => $unitPrice,
            'rental_start'  => $startDate,
            'rental_end'    => $endDate,
            'rental_days'   => $days,
            'line_total'    => $lineTotal,
        ];

        // Add to session cart
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $_SESSION['cart'][] = $cartItem;

        $_SESSION['success'] = htmlspecialchars($product['name']) . ' added to cart.';
        header('Location: /cart'); exit;
    }

    /** GET /cart */
    public function viewCart(): void {
        AuthController::requireLogin();
        $cart  = $_SESSION['cart'] ?? [];
        $total = array_sum(array_column($cart, 'line_total'));
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/cart.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    /** POST /cart/remove */
    public function removeFromCart(): void {
        AuthController::requireLogin();
        $index = (int) ($_POST['index'] ?? -1);
        if (isset($_SESSION['cart'][$index])) {
            array_splice($_SESSION['cart'], $index, 1);
            $_SESSION['success'] = 'Item removed from cart.';
        }
        header('Location: /cart'); exit;
    }

    /** POST /cart/clear */
    public function clearCart(): void {
        AuthController::requireLogin();
        $_SESSION['cart'] = [];
        header('Location: /cart'); exit;
    }

    // ── CHECKOUT ─────────────────────────────────────────────────────────────

    /** GET /checkout */
    public function checkout(): void {
        AuthController::requireLogin();
        if (empty($_SESSION['cart'])) {
            header('Location: /cart'); exit;
        }
        $cart  = $_SESSION['cart'];
        $total = array_sum(array_column($cart, 'line_total'));
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/checkout.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    /** POST /checkout/place */
    public function placeOrder(): void {
        AuthController::requireLogin();
        if (empty($_SESSION['cart'])) {
            header('Location: /cart'); exit;
        }

        $delivery = [
            'name'    => trim($_POST['delivery_name']    ?? ''),
            'address' => trim($_POST['delivery_address'] ?? ''),
            'notes'   => trim($_POST['notes']            ?? ''),
        ];

        if (!$delivery['name'] || !$delivery['address']) {
            $_SESSION['error'] = 'Please fill in all delivery details.';
            header('Location: /checkout'); exit;
        }

        try {
            $orderId = $this->orderModel->placeOrder(
                (int) $_SESSION['user_id'],
                $_SESSION['cart'],
                $delivery
            );
            $_SESSION['cart'] = []; // Clear cart
            $order = $this->orderModel->findById($orderId);
            $_SESSION['last_order'] = $order;
            header('Location: /order/confirmation'); exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Order could not be placed. Please try again.';
            header('Location: /checkout'); exit;
        }
    }

    /** GET /order/confirmation */
    public function confirmation(): void {
        AuthController::requireLogin();
        $order = $_SESSION['last_order'] ?? null;
        if (!$order) { header('Location: /catalogue'); exit; }
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/order_confirmation.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    /** GET /orders */
    public function myOrders(): void {
        AuthController::requireLogin();
        $orders = $this->orderModel->getByUser((int) $_SESSION['user_id']);
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/my_orders.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    // ── ADMIN ─────────────────────────────────────────────────────────────────

    /** GET /admin/orders */
    public function adminOrders(): void {
        AuthController::requireAdmin();
        $status = $_GET['status'] ?? '';
        $orders = $this->orderModel->getAll($status);
        require APP_ROOT . '/app/views/shared/admin_layout_top.php';
        require APP_ROOT . '/app/views/admin/orders.php';
        require APP_ROOT . '/app/views/shared/admin_layout_bottom.php';
    }

    /** GET /admin/orders/{id} */
    public function adminOrderDetail(int $id): void {
        AuthController::requireAdmin();
        $order = $this->orderModel->findById($id);
        if (!$order) { header('Location: /admin/orders'); exit; }
        require APP_ROOT . '/app/views/shared/admin_layout_top.php';
        require APP_ROOT . '/app/views/admin/order_detail.php';
        require APP_ROOT . '/app/views/shared/admin_layout_bottom.php';
    }

    /** POST /admin/orders/{id}/status */
    public function adminUpdateStatus(int $id): void {
        AuthController::requireAdmin();
        $status = $_POST['status'] ?? '';
        $this->orderModel->updateStatus($id, $status);
        $_SESSION['success'] = 'Order status updated.';
        header("Location: /admin/orders/$id"); exit;
    }
}
