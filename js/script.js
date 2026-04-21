// ============================================
// OmniMart - Main JavaScript
// ============================================

let products = [];
let cart = JSON.parse(localStorage.getItem('omnimart_cart')) || [];

// ============================================
// INIT
// ============================================

document.addEventListener('DOMContentLoaded', function () {
  initCart();
  initSlider();
  initUserMenu();
  fetchAndDisplayProducts();
});

// ============================================
// FETCH PRODUCTS
// ============================================

function fetchAndDisplayProducts() {
  const productGrid = document.getElementById('productGrid');
  if (!productGrid) return;

  productGrid.innerHTML = `
    <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted);">
      <div style="font-size:30px;margin-bottom:8px;">⏳</div> Loading products...
    </div>`;

  fetch('backend/api/get-products.php')
    .then(r => r.json())
    .then(data => {
      products = data;
      displayProducts('all');
    })
    .catch(() => {
      productGrid.innerHTML = `
        <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted);">
          <div style="font-size:30px;margin-bottom:8px;">📦</div>
          No products found. Make sure your database is set up correctly.
        </div>`;
    });
}

// ============================================
// DISPLAY PRODUCTS
// ============================================

function displayProducts(filter = 'all', search = '') {
  const productGrid = document.getElementById('productGrid');
  if (!productGrid) return;

  let filtered = products.filter(p =>
    (filter === 'all' || p.category === filter) &&
    p.name.toLowerCase().includes(search.toLowerCase())
  );

  if (filtered.length === 0) {
    productGrid.innerHTML = `
      <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted);">
        <div style="font-size:40px;margin-bottom:12px;">🔍</div>
        No products found for your search.
      </div>`;
    return;
  }

  productGrid.innerHTML = filtered.map(p => {
    const discount = p.original_price > p.price
      ? Math.round((1 - p.price / p.original_price) * 100) : 0;
    const stars = getStars(parseFloat(p.rating) || 4.0);

    return `
      <div class="product-card">
        <a href="product.php?id=${p.id}">
          <div class="product-img-wrap">
            <img src="${p.image}" alt="${p.name}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=600&q=80'">
            ${discount > 0 ? `<span class="product-badge sale">${discount}% OFF</span>` : ''}
            <button class="product-wishlist" onclick="toggleWishlist(event, ${p.id})" title="Add to Wishlist">♡</button>
          </div>
        </a>
        <div class="product-info">
          ${p.brand ? `<div class="product-brand">${p.brand}</div>` : ''}
          <a href="product.php?id=${p.id}">
            <div class="product-name">${p.name}</div>
          </a>
          <div class="product-rating">
            <span class="stars">${stars}</span>
            <span class="rating-count">(${p.reviews_count || 0})</span>
          </div>
          <div class="product-pricing">
            <div>
              <span class="product-price">₹${Number(p.price).toLocaleString('en-IN')}</span>
              ${p.original_price > p.price ? `<br><span class="product-original">₹${Number(p.original_price).toLocaleString('en-IN')}</span>` : ''}
            </div>
            ${discount > 0 ? `<span class="product-discount">Save ${discount}%</span>` : ''}
          </div>
          <button class="add-to-cart-btn" onclick="addToCart(${p.id})">
            🛒 Add to Cart
          </button>
        </div>
      </div>
    `;
  }).join('');
}

function getStars(rating) {
  const full = Math.floor(rating);
  const half = rating % 1 >= 0.5 ? 1 : 0;
  const empty = 5 - full - half;
  return '★'.repeat(full) + (half ? '⯨' : '') + '☆'.repeat(empty);
}

// ============================================
// CART FUNCTIONS
// ============================================

function initCart() {
  updateCartUI();

  // Cart toggle button
  const cartBtn = document.getElementById('cartBtn');
  const cartOverlay = document.getElementById('cartOverlay');
  const cartClose = document.getElementById('cartClose');
  const cartSidebar = document.getElementById('cartSidebar');

  if (cartBtn) {
    cartBtn.addEventListener('click', () => openCart());
  }

  if (cartClose) {
    cartClose.addEventListener('click', () => closeCart());
  }

  if (cartOverlay) {
    cartOverlay.addEventListener('click', () => closeCart());
  }
}

function openCart() {
  document.getElementById('cartSidebar')?.classList.add('open');
  document.getElementById('cartOverlay')?.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeCart() {
  document.getElementById('cartSidebar')?.classList.remove('open');
  document.getElementById('cartOverlay')?.classList.remove('open');
  document.body.style.overflow = '';
}

function addToCart(id) {
  const product = products.find(p => p.id == id);
  if (!product) return;

  const existing = cart.find(item => item.id == id);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ ...product, qty: 1 });
  }

  saveCart();
  updateCartUI();
  showToast(`✅ "${product.name}" added to cart!`, 'success');
  openCart();
}

function removeFromCart(id) {
  cart = cart.filter(item => item.id != id);
  saveCart();
  updateCartUI();
}

function changeQty(id, delta) {
  const item = cart.find(item => item.id == id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) removeFromCart(id);
  else { saveCart(); updateCartUI(); }
}

function saveCart() {
  localStorage.setItem('omnimart_cart', JSON.stringify(cart));
}

function updateCartUI() {
  const cartItems = document.getElementById('cartItems');
  const cartCount = document.getElementById('cartCount');
  const cartTotal = document.getElementById('cartTotal');

  const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  const count = cart.reduce((sum, item) => sum + item.qty, 0);

  if (cartCount) cartCount.textContent = count;
  if (cartTotal) cartTotal.textContent = '₹' + Number(total).toLocaleString('en-IN');

  if (!cartItems) return;

  if (cart.length === 0) {
    cartItems.innerHTML = `
      <div class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <p>Your cart is empty</p>
      </div>`;
    return;
  }

  cartItems.innerHTML = cart.map(item => `
    <div class="cart-item">
      <img class="cart-item-img" src="${item.image}" alt="${item.name}" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=200&q=80'">
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">₹${Number(item.price * item.qty).toLocaleString('en-IN')}</div>
        <div class="cart-item-qty">
          <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
          <span>${item.qty}</span>
          <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
        </div>
      </div>
      <button class="cart-item-remove" onclick="removeFromCart(${item.id})" title="Remove">✕</button>
    </div>
  `).join('');
}

// Expose for product.php
function addToCartDirect(product) {
  const existing = cart.find(item => item.id == product.id);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ ...product, qty: 1 });
  }
  saveCart();
  updateCartUI();
  showToast(`✅ "${product.name}" added to cart!`, 'success');
}

// ============================================
// HERO SLIDER
// ============================================

function initSlider() {
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.slider-dot');
  if (!slides.length) return;

  let current = 0;
  let timer;

  function showSlide(idx) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[idx].classList.add('active');
    if (dots[idx]) dots[idx].classList.add('active');
    current = idx;
  }

  function next() { showSlide((current + 1) % slides.length); }
  function prev() { showSlide((current - 1 + slides.length) % slides.length); }

  function startTimer() {
    clearInterval(timer);
    timer = setInterval(next, 4500);
  }

  document.querySelector('.slider-btn.next')?.addEventListener('click', () => { next(); startTimer(); });
  document.querySelector('.slider-btn.prev')?.addEventListener('click', () => { prev(); startTimer(); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => { showSlide(i); startTimer(); });
  });

  showSlide(0);
  startTimer();
}

// ============================================
// USER MENU
// ============================================

function initUserMenu() {
  const toggle = document.querySelector('.user-toggle');
  if (toggle) {
    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      toggle.parentElement.querySelector('.user-dropdown').style.display =
        toggle.parentElement.querySelector('.user-dropdown').style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', () => {
      const dd = document.querySelector('.user-dropdown');
      if (dd) dd.style.display = 'none';
    });
  }
}

// ============================================
// WISHLIST
// ============================================

let wishlist = JSON.parse(localStorage.getItem('omnimart_wishlist')) || [];

function toggleWishlist(e, id) {
  e.preventDefault();
  e.stopPropagation();
  const btn = e.currentTarget;
  const idx = wishlist.indexOf(id);
  if (idx === -1) {
    wishlist.push(id);
    btn.classList.add('active');
    btn.textContent = '♥';
    showToast('❤️ Added to wishlist!', 'success');
  } else {
    wishlist.splice(idx, 1);
    btn.classList.remove('active');
    btn.textContent = '♡';
    showToast('Removed from wishlist', '');
  }
  localStorage.setItem('omnimart_wishlist', JSON.stringify(wishlist));
}

// ============================================
// TOAST
// ============================================

function showToast(message, type = '') {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.textContent = message;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.animation = 'slideInRight 0.3s ease reverse';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ============================================
// SEARCH
// ============================================

document.querySelector('.search-box')?.addEventListener('submit', function (e) {
  // default form submission to products.php will handle it
});

// On products page, trigger display update
window.filterByCategory = function (cat) {
  displayProducts(cat);
};

window.searchProducts = function (q) {
  displayProducts('all', q);
};
