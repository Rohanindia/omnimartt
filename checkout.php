<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: frontend/login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - OmniMart</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<section style="padding:40px;max-width:1000px;margin:0 auto">
  <h2 style="font-family:var(--font-display);font-size:28px;font-weight:700;margin-bottom:28px">
    🛒 Checkout
  </h2>

  <div style="display:grid;grid-template-columns:1fr 380px;gap:30px;align-items:start">
    <!-- ORDER FORM -->
    <div>
      <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;margin-bottom:20px">
        <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:20px">📍 Shipping Address</h3>
        <form id="checkoutForm" action="backend/api/place-order.php" method="POST" onsubmit="return sendCartData()">
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>" required>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" name="phone" placeholder="+91 98765 43210" required>
            </div>
            <div class="form-group">
              <label>PIN Code</label>
              <input type="text" name="pincode" placeholder="400001" required>
            </div>
          </div>
          <div class="form-group">
            <label>Full Address</label>
            <textarea name="address" rows="3" placeholder="House/Flat No., Street, Area..." required></textarea>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            <div class="form-group">
              <label>City</label>
              <input type="text" name="city" placeholder="Hubli" required>
            </div>
            <div class="form-group">
              <label>State</label>
              <input type="text" name="state" placeholder="Karnataka" required>
            </div>
          </div>
          <input type="hidden" name="cartData" id="cartDataInput">
          <input type="hidden" name="payment_method" id="paymentMethodInput" value="COD">
        </form>
      </div>

      <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px">
        <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:16px">💳 Payment Method</h3>
        <div style="display:flex;flex-direction:column;gap:10px">
          <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--dark-4);border:2px solid var(--primary);border-radius:10px;cursor:pointer">
            <input type="radio" name="payment" value="cod" checked style="accent-color:var(--primary)">
            <span>💵 Cash on Delivery</span>
          </label>
          <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--dark-4);border:1px solid var(--border);border-radius:10px;cursor:pointer">
            <input type="radio" name="payment" value="upi" style="accent-color:var(--primary)">
            <span>📱 UPI Payment</span>
          </label>
          <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--dark-4);border:1px solid var(--border);border-radius:10px;cursor:pointer">
            <input type="radio" name="payment" value="card" style="accent-color:var(--primary)">
            <span>💳 Credit / Debit Card</span>
          </label>
        </div>
      </div>
    </div>

    <!-- ORDER SUMMARY -->
    <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;position:sticky;top:90px">
      <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:16px">📦 Order Summary</h3>
      <div id="checkoutItems" style="border-bottom:1px solid var(--border);padding-bottom:16px;margin-bottom:16px">
        <!-- filled by JS -->
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:14px;color:var(--text-secondary)">
        <span>Subtotal</span><span id="checkoutSubtotal">₹0</span>
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:14px;color:var(--text-secondary)">
        <span>Delivery</span><span style="color:var(--success)">FREE</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:14px 0;border-top:1px solid var(--border);margin-top:8px">
        <span style="font-size:16px;font-weight:600">Total</span>
        <strong style="font-family:var(--font-display);font-size:24px;color:var(--primary)" id="checkoutTotal">₹0</strong>
      </div>
      <button class="form-submit" onclick="document.getElementById('checkoutForm').requestSubmit()" style="margin-top:4px">
        ✅ Place Order
      </button>
      <div style="text-align:center;margin-top:12px;font-size:12px;color:var(--text-muted)">
        🔒 Secured with 256-bit SSL Encryption
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Render checkout summary
  const cartRaw = localStorage.getItem('omnimart_cart');
  const cart = cartRaw ? JSON.parse(cartRaw) : [];

  const itemsDiv = document.getElementById('checkoutItems');
  const subtotalEl = document.getElementById('checkoutSubtotal');
  const totalEl = document.getElementById('checkoutTotal');

  if (!cart || cart.length === 0) {
    window.location.href = 'index.php';
    return;
  }

  let total = 0;
  itemsDiv.innerHTML = cart.map(item => {
    // Robustly parse price — handles string "3000.00", number 3000, or "3,000"
    const price = parseFloat(String(item.price).replace(/,/g, '')) || 0;
    const qty = parseInt(item.qty) || 1;
    const lineTotal = price * qty;
    total += lineTotal;
    return `<div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
      <img src="${item.image || ''}" style="width:48px;height:48px;border-radius:8px;object-fit:cover;background:#333"
        onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=200&q=80'">
      <div style="flex:1;min-width:0">
        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${item.name}</div>
        <div style="font-size:12px;color:var(--text-muted)">Qty: ${qty}</div>
      </div>
      <div style="font-size:14px;font-weight:600">₹${lineTotal.toLocaleString('en-IN')}</div>
    </div>`;
  }).join('');

  subtotalEl.textContent = '₹' + total.toLocaleString('en-IN');
  totalEl.textContent = '₹' + total.toLocaleString('en-IN');

  // Make sendCartData available globally for form onsubmit
  window.sendCartData = function() {
    // Re-read cart fresh at submit time
    const freshCart = JSON.parse(localStorage.getItem('omnimart_cart') || '[]');
    // Normalize prices to plain numbers before sending
    const normalizedCart = freshCart.map(item => ({
      ...item,
      price: parseFloat(String(item.price).replace(/,/g, '')) || 0,
      qty: parseInt(item.qty) || 1
    }));
    document.getElementById('cartDataInput').value = JSON.stringify(normalizedCart);
    const selectedPayment = document.querySelector('input[name="payment"]:checked');
    if (selectedPayment) {
      document.getElementById('paymentMethodInput').value = selectedPayment.value;
    }
    return true;
  };
});
</script>
</body>
</html>
