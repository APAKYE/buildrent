<?php
// public/index.php — Front Controller (single entry point for all requests)

ob_start(); // Buffer output to prevent "headers already sent" errors

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/config/database.php';

// ── Session ───────────────────────────────────────────────────────────────────
session_start();

// Session expiry check
if (!empty($_SESSION['logged_in'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_LIFETIME) {
        session_unset();
        session_destroy();
        header('Location: /login?expired=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// ── CSRF Token ────────────────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ── Load Controllers ──────────────────────────────────────────────────────────
require_once APP_ROOT . '/app/controllers/AuthController.php';
require_once APP_ROOT . '/app/controllers/ProductController.php';
require_once APP_ROOT . '/app/controllers/OrderController.php';
require_once APP_ROOT . '/app/controllers/AdminController.php';

// ── Router ────────────────────────────────────────────────────────────────────
$uri    = strtok($_SERVER['REQUEST_URI'], '?');
$uri    = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// Extract numeric ID from URI segment
function extractId(string $uri, string $prefix, string $suffix = ''): int {
    $pattern = '#^' . preg_quote($prefix, '#') . '(\d+)' . preg_quote($suffix, '#') . '$#';
    if (preg_match($pattern, $uri, $m)) return (int) $m[1];
    return 0;
}

$auth    = new AuthController();
$product = new ProductController();
$order   = new OrderController();
$admin   = new AdminController();

// ── Public routes ─────────────────────────────────────────────────────────────
match(true) {
    // Home — redirect to catalogue if logged in, else login
    $uri === '/' => (function() use ($auth) {
        if (AuthController::isLoggedIn()) {
            header('Location: /catalogue'); exit;
        }
        header('Location: /login'); exit;
    })(),

    // Auth
    $uri === '/login'    && $method === 'GET'  => $auth->showLogin(),
    $uri === '/login'    && $method === 'POST' => $auth->login(),
    $uri === '/register' && $method === 'GET'  => $auth->showRegister(),
    $uri === '/register' && $method === 'POST' => $auth->register(),
    $uri === '/logout'                         => $auth->logout(),

    // Catalogue
    $uri === '/catalogue' => $product->catalogue(),

    // Product detail: /product/123
    (bool) ($id = extractId($uri, '/product/')) => $product->detail($id),

    // Cart
    $uri === '/cart'          && $method === 'GET'  => $order->viewCart(),
    $uri === '/cart/add'      && $method === 'POST' => $order->addToCart(),
    $uri === '/cart/remove'   && $method === 'POST' => $order->removeFromCart(),
    $uri === '/cart/clear'    && $method === 'POST' => $order->clearCart(),

    // Checkout
    $uri === '/checkout'       && $method === 'GET'  => $order->checkout(),
    $uri === '/checkout/place' && $method === 'POST' => $order->placeOrder(),
    $uri === '/order/confirmation'                   => $order->confirmation(),
    $uri === '/orders'                               => $order->myOrders(),

    // Account
    $uri === '/account' => (function() {
        require_once APP_ROOT . '/config/app.php';
        AuthController::requireLogin();
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/account.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    })(),

    // ── Admin routes ──────────────────────────────────────────────────────────
    $uri === '/admin/dashboard'  => $admin->dashboard(),
    $uri === '/admin/products'   => $product->adminList(),
    $uri === '/admin/products/create'                            => $product->adminCreate(),
    $uri === '/admin/products/store' && $method === 'POST'       => $product->adminStore(),
    (bool) ($id = extractId($uri, '/admin/products/', '/edit'))  => $product->adminEdit($id),
    (bool) ($id = extractId($uri, '/admin/products/', '/update')) && $method === 'POST' => $product->adminUpdate($id),
    (bool) ($id = extractId($uri, '/admin/products/', '/delete')) && $method === 'POST' => $product->adminDelete($id),
    (bool) ($id = extractId($uri, '/admin/products/', '/prices')) && $method === 'POST' => $product->adminPrices($id),

    $uri === '/admin/orders'     => $order->adminOrders(),
    (bool) ($id = extractId($uri, '/admin/orders/', '/status')) && $method === 'POST' => $order->adminUpdateStatus($id),
    (bool) ($id = extractId($uri, '/admin/orders/'))             => $order->adminOrderDetail($id),

    // 404
    default => (function() {
        http_response_code(404);
        require APP_ROOT . '/app/views/shared/layout_top.php';
        echo '<div class="empty-state"><div class="empty-icon">🔍</div><h1>404 — Page Not Found</h1><a href="/" class="btn btn-primary">Go Home</a></div>';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    })(),
};
