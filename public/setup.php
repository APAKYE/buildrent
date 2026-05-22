<?php
// public/setup.php — One-time password setup utility
// DELETE THIS FILE after use!

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/config/database.php';

$db = getDB();

// Generate fresh hashes
$hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]);

$emails = [
    'kofi@example.com',
    'admin@buildrent.com', 
    'ama@example.com',
    'yaw@example.com'
];

$updated = 0;
foreach ($emails as $email) {
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);
    $updated += $stmt->rowCount();
}

echo "<h2>Setup Complete</h2>";
echo "<p>Updated $updated user passwords.</p>";
echo "<p>Hash used: <code>$hash</code></p>";
echo "<p>All accounts now use password: <strong>password</strong></p>";
echo "<p><a href='/login'>Go to Login</a></p>";
echo "<p><strong>IMPORTANT: Delete /public/setup.php after use!</strong></p>";
