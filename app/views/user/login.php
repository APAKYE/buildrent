<?php $pageTitle = 'Login'; ?>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🏗️ BuildRent</div>
    <h1 class="auth-title">Welcome Back</h1>
    <p class="auth-sub">Log in to browse and order equipment</p>

    <form method="POST" action="/login" class="auth-form">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
               placeholder="you@example.com">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary btn-full">Log In</button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="/register">Create one free</a></p>

    <div class="auth-demo">
      <p><strong>Demo Credentials:</strong></p>
      <p>👤 Customer: <code>kofi@example.com</code> / <code>password</code></p>
      <p>🔑 Admin: <code>admin@buildrent.com</code> / <code>password</code></p>
    </div>
  </div>
</div>
<?php unset($_SESSION['old']); ?>
