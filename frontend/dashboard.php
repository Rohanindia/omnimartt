<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$base = '../';
include '../backend/config/db.php';

// Get user orders
$uid = $_SESSION['user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC LIMIT 10");
$orders = $orders ? $orders->fetch_all(MYSQLI_ASSOC) : [];

// Total spent
$totalSpent = array_sum(array_column($orders, 'total_amount'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Dashboard - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="dashboard-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <h3>My Account</h3>
      <p><?= htmlspecialchars($_SESSION['user_name']) ?></p>
    </div>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="active"><span class="nav-icon">📊</span> Dashboard</a>
      <a href="orders.php"><span class="nav-icon">📦</span> My Orders</a>
      <a href="profile.php"><span class="nav-icon">👤</span> Profile</a>
      <a href="#"><span class="nav-icon">❤️</span> Wishlist</a>
      <a href="#"><span class="nav-icon">📍</span> Addresses</a>
      <a href="../backend/api/logout.php" style="color:var(--danger);margin-top:auto"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>Welcome back, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?>! 👋</h1>
      <p>Here's your shopping activity overview</p>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-card-icon">📦</div>
        <h3>Total Orders</h3>
        <div class="value"><?= count($orders) ?></div>
        <div class="change">↑ All time</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <h3>Total Spent</h3>
        <div class="value">₹<?= number_format($totalSpent) ?></div>
        <div class="change">↑ All time</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon">❤️</div>
        <h3>Wishlist Items</h3>
        <div class="value" id="wishlistCount">-</div>
        <div class="change">Saved</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon">⭐</div>
        <h3>Member Since</h3>
        <div class="value" style="font-size:16px">2026</div>
        <div class="change">OmniMart Member</div>
      </div>
    </div>

    <!-- CHARTS -->
    <div class="charts-grid">
      <div class="chart-card">
        <h3>Spending Overview</h3>
        <canvas id="spendingChart" height="100"></canvas>
      </div>
      <div class="chart-card">
        <h3>Order Status</h3>
        <canvas id="statusChart"></canvas>
      </div>
    </div>

    <!-- RECENT ORDERS -->
    <div class="data-table">
      <h3>Recent Orders</h3>
      <?php if (empty($orders)): ?>
        <div style="text-align:center;padding:40px;color:var(--text-muted)">
          <div style="font-size:40px;margin-bottom:8px">📦</div>
          <p>No orders yet. <a href="../products.php" style="color:var(--primary)">Start shopping!</a></p>
        </div>
      <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
          <tr>
            <td>#<?= $order['id'] ?></td>
            <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
            <td>₹<?= number_format($order['total_amount']) ?></td>
            <td><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></td>
            <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
            <td><a href="orders.php?id=<?= $order['id'] ?>" style="color:var(--primary);font-size:13px">View →</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </main>
</div>

<script>
// Wishlist count
document.getElementById('wishlistCount').textContent =
  (JSON.parse(localStorage.getItem('omnimart_wishlist')) || []).length;

// Spending Chart
new Chart(document.getElementById('spendingChart'), {
  type: 'line',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun'],
    datasets: [{
      label: 'Spending (₹)',
      data: [4000,8000,5000,12000,9000,15000],
      borderColor: '#FF4F18',
      backgroundColor: 'rgba(255,79,24,0.1)',
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#FF4F18'
    }]
  },
  options: {
    plugins: { legend: { labels: { color: '#A0A0B8' } } },
    scales: {
      x: { ticks: { color: '#606078' }, grid: { color: 'rgba(255,255,255,0.05)' } },
      y: { ticks: { color: '#606078' }, grid: { color: 'rgba(255,255,255,0.05)' } }
    }
  }
});

// Status Chart
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: ['Delivered','Shipped','Processing','Pending'],
    datasets: [{
      data: [65, 15, 12, 8],
      backgroundColor: ['#22C55E', '#3b82f6', '#F59E0B', '#606078']
    }]
  },
  options: {
    plugins: { legend: { labels: { color: '#A0A0B8' } } }
  }
});
</script>
</body>
</html>
