<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OmniMart - Shop Everything You Love</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🛒</text></svg>">
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
    <div class="cart-subtotal">
      <span>Subtotal</span>
      <span id="cartTotal">₹0</span>
    </div>
    <div class="cart-subtotal">
      <span>Delivery</span>
      <span style="color:var(--success)">FREE</span>
    </div>
    <div class="cart-total-row">
      <span>Total</span>
      <strong id="cartGrandTotal">₹0</strong>
    </div>
    <button class="checkout-btn" onclick="window.location.href='checkout.php'">
      Proceed to Checkout →
    </button>
  </div>
</aside>

<!-- HERO SLIDER -->
<section class="hero">
  <div class="slider">
    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1600&q=80" class="slide active" alt="Electronics Sale">
    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80" class="slide" alt="Fashion">
    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1600&q=80" class="slide" alt="Home Living">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="hero-badge">🔥 Limited Time Offer</div>
      <h1>Shop the<br>Future, Today</h1>
      <p>Discover premium electronics, fashion, home decor and more at unbeatable prices. Fast delivery guaranteed.</p>
      <div class="hero-cta">
        <a href="products.php" class="btn-primary">Shop Now →</a>
        <a href="categories.php" class="btn-outline">Browse Categories</a>
      </div>
    </div>
    <div class="slider-nav">
      <button class="slider-dot active"></button>
      <button class="slider-dot"></button>
      <button class="slider-dot"></button>
    </div>
    <div class="slider-arrows">
      <button class="slider-btn prev">&#8592;</button>
      <button class="slider-btn next">&#8594;</button>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
  <div class="stat-item">
    <span class="stat-icon">🚀</span>
    <div class="stat-text">
      <strong>Free Delivery</strong>
      <span>On orders above ₹999</span>
    </div>
  </div>
  <div class="stat-item">
    <span class="stat-icon">🔒</span>
    <div class="stat-text">
      <strong>Secure Payment</strong>
      <span>100% protected transactions</span>
    </div>
  </div>
  <div class="stat-item">
    <span class="stat-icon">↩️</span>
    <div class="stat-text">
      <strong>Easy Returns</strong>
      <span>30-day return policy</span>
    </div>
  </div>
  <div class="stat-item">
    <span class="stat-icon">🎧</span>
    <div class="stat-text">
      <strong>24/7 Support</strong>
      <span>Always here to help</span>
    </div>
  </div>
</div>

<!-- CATEGORIES SECTION -->
<section>
  <div class="section-header">
    <div>
      <h2 class="section-title">Shop by Category</h2>
      <p class="section-subtitle">Explore our wide range of product categories</p>
    </div>
    <a href="categories.php" class="view-all">View all categories →</a>
  </div>
  <div class="categories-grid">
    <a href="products.php?category=electronics" class="category-card">
      <img class="category-img" src="https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&q=80" alt="Electronics">
      <div class="category-overlay"></div>
      <div class="category-info">
        <h3>Electronics</h3>
        <span>Phones, Laptops & More</span>
      </div>
      <span class="category-badge">HOT</span>
    </a>
    <a href="products.php?category=fashion" class="category-card">
      <img class="category-img" src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&q=80" alt="Fashion">
      <div class="category-overlay"></div>
      <div class="category-info">
        <h3>Fashion</h3>
        <span>Clothing & Accessories</span>
      </div>
    </a>
    <a href="products.php?category=home" class="category-card">
      <img class="category-img" src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&q=80" alt="Home">
      <div class="category-overlay"></div>
      <div class="category-info">
        <h3>Home & Living</h3>
        <span>Furniture & Decor</span>
      </div>
    </a>
    <a href="products.php?category=sports" class="category-card">
      <img class="category-img" src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=400&q=80" alt="Sports">
      <div class="category-overlay"></div>
      <div class="category-info">
        <h3>Sports</h3>
        <span>Fitness & Outdoor</span>
      </div>
    </a>
    <a href="products.php?category=books" class="category-card">
      <img class="category-img" src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&q=80" alt="Books">
      <div class="category-overlay"></div>
      <div class="category-info">
        <h3>Books</h3>
        <span>Knowledge & Learning</span>
      </div>
    </a>
    <a href="products.php?category=beauty" class="category-card">
      <img class="category-img" src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&q=80" alt="Beauty">
      <div class="category-overlay"></div>
      <div class="category-info">
        <h3>Beauty</h3>
        <span>Skincare & Wellness</span>
      </div>
      <span class="category-badge">NEW</span>
    </a>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<section style="background:var(--dark-2);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
  <div class="section-header">
    <div>
      <h2 class="section-title">Featured Products</h2>
      <p class="section-subtitle">Handpicked deals just for you</p>
    </div>
    <a href="products.php" class="view-all">View all →</a>
  </div>
  <div id="productGrid" class="product-grid">
    <!-- Loaded by JS -->
  </div>
</section>

<!-- PROMO BANNERS -->
<section>
  <div class="promo-grid">
    <div class="promo-card">
      <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&q=80" alt="Electronics Sale">
      <div class="promo-content">
        <span class="promo-tag">Deal of the Day</span>
        <h3>Electronics Sale</h3>
        <p>Up to 40% off on premium gadgets</p>
        <a href="products.php?category=electronics" class="btn-primary" style="align-self:flex-start;">Shop Now →</a>
      </div>
    </div>
    <div class="promo-card">
      <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=800&q=80" alt="Fashion">
      <div class="promo-content">
        <span class="promo-tag">New Arrivals</span>
        <h3>Fashion Week</h3>
        <p>Latest styles at amazing prices</p>
        <a href="products.php?category=fashion" class="btn-primary" style="align-self:flex-start;">Explore →</a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/script.js"></script>
<script>
  // Sync grand total
  function syncGrandTotal() {
    const ct = document.getElementById('cartTotal');
    const gt = document.getElementById('cartGrandTotal');
    if (ct && gt) gt.textContent = ct.textContent;
  }
  const observer = new MutationObserver(syncGrandTotal);
  const cartTotalEl = document.getElementById('cartTotal');
  if (cartTotalEl) observer.observe(cartTotalEl, { childList: true, characterData: true, subtree: true });
</script>
</body>
</html>
