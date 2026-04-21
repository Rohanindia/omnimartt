<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$base = '../';
include '../backend/config/db.php';

$uid = $_SESSION['user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");
$orders = $orders ? $orders->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div style="background:rgba(34,197,94,0.15);border:1px solid #22C55E;border-radius:12px;padding:16px 24px;max-width:900px;margin:20px auto;display:flex;align-items:center;gap:12px;color:#22C55E;font-weight:600;">
  ✅ Order #<?= intval($_GET['order_id'] ?? 0) ?> placed successfully! Your order is now pending.
</div>
<?php endif; ?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="sidebar-logo"><h3>My Account</h3><p><?= htmlspecialchars($_SESSION['user_name']) ?></p></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php"><span class="nav-icon">📊</span> Dashboard</a>
      <a href="orders.php" class="active"><span class="nav-icon">📦</span> My Orders</a>
      <a href="profile.php"><span class="nav-icon">👤</span> Profile</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>📦 My Orders</h1>
      <p><?= count($orders) ?> order<?= count($orders) != 1 ? 's' : '' ?> placed</p>
    </div>

    <?php if (empty($orders)): ?>
      <div style="text-align:center;padding:80px;color:var(--text-muted)">
        <div style="font-size:60px;margin-bottom:16px">📦</div>
        <h3 style="margin-bottom:8px">No orders yet</h3>
        <p style="margin-bottom:24px">You haven't placed any orders yet.</p>
        <a href="../products.php" class="btn-primary">Start Shopping →</a>
      </div>
    <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:16px">
        <?php foreach ($orders as $order):
          $items = $conn->query("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=".$order['id']);
          $items = $items ? $items->fetch_all(MYSQLI_ASSOC) : [];
        ?>
        <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden">
          <!-- Order Header -->
          <div style="padding:16px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
            <div style="display:flex;gap:30px;flex-wrap:wrap">
              <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:2px">ORDER ID</div>
                <div style="font-weight:600">#<?= $order['id'] ?></div>
              </div>
              <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:2px">DATE</div>
                <div style="font-weight:600"><?= date('d M Y', strtotime($order['created_at'])) ?></div>
              </div>
              <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:2px">TOTAL</div>
                <div style="font-weight:600;color:var(--primary)">₹<?= number_format($order['total_amount']) ?></div>
              </div>
            </div>
            <span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
          </div>
          <!-- Order Items -->
          <div style="padding:16px 24px">
            <?php foreach ($items as $item): ?>
            <div style="display:flex;align-items:center;gap:14px;padding:10px 0;border-bottom:1px solid var(--border)">
              <img src="<?= htmlspecialchars($item['image'] ?? '') ?>" 
                   style="width:56px;height:56px;border-radius:8px;object-fit:cover;background:var(--dark-4)"
                   onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=200&q=80'">
              <div style="flex:1">
                <div style="font-size:14px;font-weight:500"><?= htmlspecialchars($item['name']) ?></div>
                <div style="font-size:12px;color:var(--text-muted)">Qty: <?= $item['quantity'] ?></div>
              </div>
              <div style="font-size:14px;font-weight:600">₹<?= number_format($item['price'] * $item['quantity']) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
</div>

<script src="../js/script.js"></script>
</body>
</html>
