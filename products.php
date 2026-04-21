<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include 'backend/config/db.php';

$category = $_GET['category'] ?? 'all';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'default';

// Build query
$where = "WHERE 1=1";
if ($category !== 'all' && !empty($category)) {
    $category_safe = $conn->real_escape_string($category);
    $where .= " AND category = '$category_safe'";
}
if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $where .= " AND (name LIKE '%$search_safe%' OR description LIKE '%$search_safe%' OR brand LIKE '%$search_safe%')";
}

$order = "ORDER BY created_at DESC";
if ($sort === 'price_low') $order = "ORDER BY price ASC";
if ($sort === 'price_high') $order = "ORDER BY price DESC";
if ($sort === 'rating') $order = "ORDER BY rating DESC";

$result = $conn->query("SELECT * FROM products $where $order");
$products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$total = count($products);

$categoryLabels = [
    'all' => 'All Products',
    'electronics' => '⚡ Electronics',
    'fashion' => '👗 Fashion',
    'home' => '🏠 Home & Living',
    'sports' => '🏋️ Sports',
    'books' => '📚 Books',
    'beauty' => '💄 Beauty',
];

$pageTitle = !empty($search) ? "Search: $search" : ($categoryLabels[$category] ?? 'All Products');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> - OmniMart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<!-- CART -->
<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <h2>🛒 Your Cart</h2>
    <button class="cart-close" id="cartClose">✕</button>
  </div>
  <div class="cart-items" id="cartItems"></div>
  <div class="cart-footer">
    <div class="cart-total-row">
      <span>Total</span>
      <strong id="cartTotal">₹0</strong>
    </div>
    <button class="checkout-btn" onclick="window.location.href='checkout.php'">Proceed to Checkout →</button>
  </div>
</aside>

<div class="products-layout">
  <!-- FILTERS -->
  <aside class="filters-panel">
    <h3>🔧 Filters</h3>

    <div class="filter-section">
      <h4>Category</h4>
      <?php foreach ($categoryLabels as $slug => $label): ?>
        <div class="filter-option">
          <input type="radio" name="cat_filter" value="<?= $slug ?>"
            <?= $category === $slug ? 'checked' : '' ?>
            onchange="location.href='products.php?category='+this.value">
          <span><?= $label ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="filter-section">
      <h4>Sort By</h4>
      <select class="sort-select" style="width:100%" onchange="location.href='products.php?category=<?= $category ?>&sort='+this.value">
        <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Default</option>
        <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Top Rated</option>
      </select>
    </div>
  </aside>

  <!-- PRODUCTS MAIN -->
  <div class="products-main">
    <div class="products-toolbar">
      <div>
        <h2><?= htmlspecialchars($pageTitle) ?></h2>
        <p class="products-count"><?= $total ?> product<?= $total != 1 ? 's' : '' ?> found</p>
      </div>
      <select class="sort-select" onchange="location.href='products.php?category=<?= $category ?>&sort='+this.value">
        <option value="default">Sort: Default</option>
        <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low → High</option>
        <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High → Low</option>
        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Top Rated</option>
      </select>
    </div>

    <?php if (empty($products)): ?>
      <div style="text-align:center;padding:80px 20px;color:var(--text-muted)">
        <div style="font-size:60px;margin-bottom:16px">🔍</div>
        <h3 style="margin-bottom:8px">No products found</h3>
        <p>Try adjusting your search or filters</p>
        <a href="products.php" class="btn-primary" style="margin-top:20px;display:inline-flex">View All Products</a>
      </div>
    <?php else: ?>
    <div class="product-grid">
      <?php foreach ($products as $p):
        $discount = ($p['original_price'] > $p['price']) ? round((1 - $p['price'] / $p['original_price']) * 100) : 0;
        $stars = str_repeat('★', floor($p['rating'])) . str_repeat('☆', 5 - floor($p['rating']));
      ?>
        <div class="product-card">
          <a href="product.php?id=<?= $p['id'] ?>">
            <div class="product-img-wrap">
              <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy"
                onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=600&q=80'">
              <?php if ($discount > 0): ?>
                <span class="product-badge sale"><?= $discount ?>% OFF</span>
              <?php endif; ?>
              <button class="product-wishlist" title="Wishlist">♡</button>
            </div>
          </a>
          <div class="product-info">
            <?php if ($p['brand']): ?><div class="product-brand"><?= htmlspecialchars($p['brand']) ?></div><?php endif; ?>
            <a href="product.php?id=<?= $p['id'] ?>">
              <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
            </a>
            <div class="product-rating">
              <span class="stars"><?= $stars ?></span>
              <span class="rating-count">(<?= number_format($p['reviews_count']) ?>)</span>
            </div>
            <div class="product-pricing">
              <div>
                <span class="product-price">₹<?= number_format($p['price']) ?></span>
                <?php if ($discount > 0): ?>
                  <br><span class="product-original">₹<?= number_format($p['original_price']) ?></span>
                <?php endif; ?>
              </div>
              <?php if ($discount > 0): ?><span class="product-discount">Save <?= $discount ?>%</span><?php endif; ?>
            </div>
            <button class="add-to-cart-btn" onclick="addProductToCart(<?= htmlspecialchars(json_encode($p)) ?>)">
              🛒 Add to Cart
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
<script>
function addProductToCart(product) {
  let cart = JSON.parse(localStorage.getItem('omnimart_cart')) || [];
  const existing = cart.find(item => item.id == product.id);
  if (existing) existing.qty++;
  else cart.push({...product, qty: 1});
  localStorage.setItem('omnimart_cart', JSON.stringify(cart));
  // Refresh cart UI
  if (typeof updateCartUI === 'function') {
    // Update global cart
    window.cart = cart;
    updateCartUI();
  }
  showToast('✅ Added to cart!', 'success');
  openCart();
}
</script>
</body>
</html>
