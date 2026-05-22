<?php
// config/app.php — Application-wide constants

define('APP_NAME',    'BuildRent');
define('APP_VERSION', '1.0.0');
define('BASE_URL',    '/');
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Session config
define('SESSION_LIFETIME', 1800); // 30 minutes

// Pagination
define('ITEMS_PER_PAGE', 12);

// Upload path (for future image uploads)
define('UPLOAD_PATH', APP_ROOT . '/public/images/products/');

// Password policy
define('PASSWORD_MIN_LENGTH', 8);
