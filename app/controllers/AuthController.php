<?php
// app/controllers/AuthController.php

require_once APP_ROOT . '/app/models/UserModel.php';

class AuthController {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /** GET /login */
    public function showLogin(): void {
        if ($this->isLoggedIn()) $this->redirect('/');
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/login.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    /** POST /login */
    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = 'Please enter your email and password.';
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !$this->userModel->verifyPassword($password, $user['password_hash'])) {
            $_SESSION['error'] = 'Invalid email or password.';
            $this->redirect('/login');
            return;
        }

        // Regenerate session ID on login (prevents session fixation)
        session_regenerate_id(true);

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        if ($user['role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/catalogue');
        }
    }

    /** GET /register */
    public function showRegister(): void {
        if ($this->isLoggedIn()) $this->redirect('/');
        require APP_ROOT . '/app/views/shared/layout_top.php';
        require APP_ROOT . '/app/views/user/register.php';
        require APP_ROOT . '/app/views/shared/layout_bottom.php';
    }

    /** POST /register */
    public function register(): void {
        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $phone    = trim($_POST['phone']    ?? '');
        $password = $_POST['password']      ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        // Validation
        $errors = [];
        if (strlen($name) < 2)      $errors[] = 'Name must be at least 2 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if (strlen($password) < PASSWORD_MIN_LENGTH)    $errors[] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.';
        if ($password !== $confirm)  $errors[] = 'Passwords do not match.';
        if ($this->userModel->findByEmail($email))      $errors[] = 'An account with this email already exists.';

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = compact('name', 'email', 'phone');
            $this->redirect('/register');
            return;
        }

        $this->userModel->create($name, $email, $password, $phone);
        $_SESSION['success'] = 'Account created successfully! Please log in.';
        $this->redirect('/login');
    }

    /** GET /logout */
    public function logout(): void {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public static function isLoggedIn(): bool {
        return !empty($_SESSION['logged_in']);
    }

    public static function isAdmin(): bool {
        return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = 'Please log in to continue.';
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            die('<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>');
        }
    }

    private function redirect(string $path): void {
        header("Location: $path");
        exit;
    }
}
