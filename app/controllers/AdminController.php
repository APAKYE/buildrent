<?php
// app/controllers/AdminController.php

require_once APP_ROOT . '/app/models/UserModel.php';
require_once APP_ROOT . '/app/models/ProductModel.php';
require_once APP_ROOT . '/app/models/OrderModel.php';
require_once APP_ROOT . '/app/controllers/AuthController.php';

class AdminController {

    private UserModel    $userModel;
    private ProductModel $productModel;
    private OrderModel   $orderModel;

    public function __construct() {
        $this->userModel    = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel   = new OrderModel();
    }

    /** GET /admin/dashboard */
    public function dashboard(): void {
        AuthController::requireAdmin();

        $totalUsers    = count($this->userModel->getAll());
        $totalProducts = count($this->productModel->getAllAdmin());
        $allOrders     = $this->orderModel->getAll();
        $totalOrders   = count($allOrders);
        $totalRevenue  = array_sum(array_column($allOrders, 'total_amount'));
        $pendingOrders = count(array_filter($allOrders, fn($o) => $o['status'] === 'pending'));
        $recentOrders  = array_slice($allOrders, 0, 5);

        require APP_ROOT . '/app/views/shared/admin_layout_top.php';
        require APP_ROOT . '/app/views/admin/dashboard.php';
        require APP_ROOT . '/app/views/shared/admin_layout_bottom.php';
    }
}
