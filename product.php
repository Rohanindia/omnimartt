<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include 'backend/config/db.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if (!$product) { header("Location: index.php"); exit; }

$discount = ($product['original_price'] > $product['price'])
    ? round((1 - $product['price'] / $product['original_price']) * 100) : 0;
$stars = str_repeat('★', floor($product['rating'])) . str_repeat('☆', 5 - floor($product['rating']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['name']) ?> - OmniMart</title>
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

<div class="product-detail">
  <!-- Breadcrumb -->
  <div style="margin-bottom:24px;font-size:13px;color:var(--text-muted)">
    <a href="index.php" style="color:var(--text-muted)">Home</a> /
    <a href="products.php?category=<?= $product['category'] ?>" style="color:var(--text-muted)"><?= ucfirst($product['category']) ?></a> /
    <span style="color:var(--text-primary)"><?= htmlspecialchars($product['name']) ?></span>
  </div>

  <div class="product-detail-grid">
    <div>
      <img class="product-detail-img" 
           src="<?= htmlspecialchars($product['image']) ?>" 
           alt="<?= htmlspecialchars($product['name']) ?>"
           onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=800&q=80'">
    </div>
    <div class="product-detail-info">
      <?php if ($product['brand']): ?>
        <div class="product-detail-brand"><?= htmlspecialchars($product['brand']) ?></div>
      <?php endif; ?>

      <h1 class="product-detail-name"><?= htmlspecialchars($product['name']) ?></h1>

      <div style="display:flex;align-items:center;gap:12px;">
        <span class="stars" style="font-size:18px"><?= $stars ?></span>
        <span style="color:var(--text-muted);font-size:14px"><?= $product['reviews_count'] ?> reviews</span>
        <?php if ($product['stock'] > 0): ?>
          <span style="color:var(--success);font-size:13px;font-weight:600">✓ In Stock</span>
        <?php else: ?>
          <span style="color:var(--danger);font-size:13px;font-weight:600">Out of Stock</span>
        <?php endif; ?>
      </div>

      <div class="product-detail-price">
        <span class="price">₹<?= number_format($product['price']) ?></span>
        <?php if ($discount > 0): ?>
          <span class="original">₹<?= number_format($product['original_price']) ?></span>
          <span class="discount"><?= $discount ?>% OFF</span>
        <?php endif; ?>
      </div>

      <?php if ($product['description']): ?>
        <p class="product-detail-desc"><?= htmlspecialchars($product['description']) ?></p>
      <?php else: ?>
        <p class="product-detail-desc">Premium quality product available at best price. Fast delivery and secure payment guaranteed. Shop with confidence on OmniMart.</p>
      <?php endif; ?>

      <div class="product-detail-actions">
        <button class="btn-primary" onclick="addToCartAndOpen()">🛒 Add to Cart</button>
        <button class="btn-primary" style="background:var(--accent);flex:0.5" onclick="buyNow()">⚡ Buy Now</button>
      </div>

      <div class="product-detail-meta">
        <div class="meta-item"><span class="meta-icon">🚀</span> Free Delivery</div>
        <div class="meta-item"><span class="meta-icon">↩️</span> 30-Day Returns</div>
        <div class="meta-item"><span class="meta-icon">🔒</span> Secure Payment</div>
        <div class="meta-item"><span class="meta-icon">✅</span> 100% Genuine</div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
<script>
const thisProduct = <?= json_encode($product) ?>;

function addToCartAndOpen() {
  let cart = JSON.parse(localStorage.getItem('omnimart_cart')) || [];
  const existing = cart.find(item => item.id == thisProduct.id);
  if (existing) existing.qty++;
  else cart.push({...thisProduct, qty: 1});
  localStorage.setItem('omnimart_cart', JSON.stringify(cart));
  window.cart = cart;
  updateCartUI();
  openCart();
}

function buyNow() {
  addToCartAndOpen();
  setTimeout(() => window.location.href = 'checkout.php', 300);
}
</script>
</body>
</html>
