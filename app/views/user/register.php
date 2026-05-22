<?php $pageTitle = 'Create Account'; $old = $_SESSION['old'] ?? []; ?>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">🏗️ BuildRent</div>
    <h1 class="auth-title">Create Account</h1>
    <p class="auth-sub">Join thousands of contractors using BuildRent</p>

    <form method="POST" action="/register" class="auth-form">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required
               value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="Kofi Mensah">
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="you@example.com">
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone"
               value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="+233 24 000 0000">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Min. 8 characters">
      </div>
      <div class="form-group">
        <label for="password_confirm">Confirm Password</label>
        <input type="password" id="password_confirm" name="password_confirm" required placeholder="Repeat password">
      </div>
      <button type="submit" class="btn btn-primary btn-full">Create Account</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="/login">Log in</a></p>
  </div>
</div>
<?php unset($_SESSION['old']); ?>
