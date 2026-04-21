<?php
// Determine base path (for nested pages)
$base = '';
$depth = substr_count($_SERVER['PHP_SELF'], '/') - 1;
if ($depth > 1) $base = '../';

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<header class="navbar">
  <!-- Logo -->
  <div class="logo">
    <a href="<?= $base ?>index.php">OmniMart</a>
  </div>

  <!-- Nav Links -->
  <nav class="nav-links">
    <a href="<?= $base ?>index.php" <?= $currentPage === 'index.php' ? 'class="active"' : '' ?>>Home</a>
    <a href="<?= $base ?>categories.php" <?= $currentPage === 'categories.php' ? 'class="active"' : '' ?>>Categories</a>
    <a href="<?= $base ?>products.php" <?= $currentPage === 'products.php' ? 'class="active"' : '' ?>>Products</a>
    <a href="<?= $base ?>about.php" <?= $currentPage === 'about.php' ? 'class="active"' : '' ?>>About</a>
    <a href="<?= $base ?>contact.php" <?= $currentPage === 'contact.php' ? 'class="active"' : '' ?>>Contact</a>
  </nav>

  <!-- Search -->
  <form action="<?= $base ?>products.php" method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  </form>

  <!-- Actions -->
  <div class="nav-actions">
    <!-- Cart Button -->
    <button class="cart-icon-btn" id="cartBtn" title="Cart">
      🛒
      <span class="cart-count" id="cartCount">0</span>
    </button>

    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="user-menu">
        <div class="user-toggle">
          👤 <?= htmlspecialchars($_SESSION['user_name']) ?> ▾
        </div>
        <div class="user-dropdown">
          <a href="<?= $base ?>frontend/dashboard.php">📊 Dashboard</a>
          <a href="<?= $base ?>frontend/orders.php">📦 My Orders</a>
          <a href="<?= $base ?>frontend/profile.php">👤 Profile</a>
          <?php if ($_SESSION['role'] === 'admin'): ?>
          <a href="<?= $base ?>admin/dashboard.php">⚙️ Admin Panel</a>
          <?php endif; ?>
          <?php if ($_SESSION['role'] === 'vendor'): ?>
          <a href="<?= $base ?>vendor/dashboard.php">🏪 Vendor Panel</a>
          <?php endif; ?>
          <a href="<?= $base ?>backend/api/logout.php" class="logout">🚪 Logout</a>
        </div>
      </div>
    <?php else: ?>
      <a href="<?= $base ?>frontend/login.php" class="login-btn">Login</a>
    <?php endif; ?>
  </div>
</header>
