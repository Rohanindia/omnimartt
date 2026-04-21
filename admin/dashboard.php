<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php"); exit;
}
$base = '../';
include '../backend/config/db.php';

// Stats
$totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'")->fetch_assoc()['total'] ?? 0;
$totalOrders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'] ?? 0;
$totalUsers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'] ?? 0;
$totalProducts = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'] ?? 0;

$recentOrders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 8");
$recentOrders = $recentOrders ? $recentOrders->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="sidebar-logo">
      <h3>⚙️ Admin Panel</h3>
      <p><?= htmlspecialchars($_SESSION['user_name']) ?></p>
    </div>
    <div class="sidebar-section-title">MANAGEMENT</div>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="active"><span class="nav-icon">📊</span> Dashboard</a>
      <a href="orders.php"><span class="nav-icon">📦</span> Orders</a>
      <a href="products.php"><span class="nav-icon">🏷️</span> Products</a>
      <a href="users.php"><span class="nav-icon">👥</span> Users</a>
    </nav>
    <div class="sidebar-section-title">ACCOUNT</div>
    <nav class="sidebar-nav">
      <a href="../index.php"><span class="nav-icon">🏠</span> Go to Store</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>Dashboard Overview 📊</h1>
      <p>Welcome back! Here's what's happening with your store</p>
    </div>

    <!-- KPI STATS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <h3>Total Revenue</h3>
        <div class="value">₹<?= number_format($totalRevenue) ?></div>
        <div class="change">↑ All time</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon">📦</div>
        <h3>Total Orders</h3>
        <div class="value"><?= number_format($totalOrders) ?></div>
        <div class="change">↑ All time</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon">👥</div>
        <h3>Total Users</h3>
        <div class="value"><?= number_format($totalUsers) ?></div>
        <div class="change">↑ Registered</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon">🏷️</div>
        <h3>Products</h3>
        <div class="value"><?= number_format($totalProducts) ?></div>
        <div class="change">↑ In catalog</div>
      </div>
    </div>

    <!-- CHARTS -->
    <div class="charts-grid">
      <div class="chart-card">
        <h3>Sales Revenue (Monthly)</h3>
        <canvas id="salesChart" height="100"></canvas>
      </div>
      <div class="chart-card">
        <h3>Order Status</h3>
        <canvas id="orderPie"></canvas>
      </div>
    </div>

    <!-- RECENT ORDERS -->
    <div class="data-table">
      <h3>Recent Orders</h3>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentOrders)): ?>
          <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px">No orders yet</td></tr>
          <?php else: ?>
          <?php foreach ($recentOrders as $order): ?>
          <tr>
            <td>#<?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['user_name']) ?></td>
            <td>₹<?= number_format($order['total_amount']) ?></td>
            <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
            <td>
              <select onchange="updateOrderStatus(<?= $order['id'] ?>, this.value)"
                style="background:var(--dark-4);border:1px solid var(--border);color:white;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer">
                <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                  <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td><a href="orders.php?id=<?= $order['id'] ?>" style="color:var(--primary);font-size:13px">View →</a></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
// Sales Chart
new Chart(document.getElementById('salesChart'), {
  type: 'line',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
    datasets: [{
      label: 'Revenue (₹)',
      data: [45000,78000,60000,95000,82000,110000,99000,135000],
      borderColor: '#FF4F18',
      backgroundColor: 'rgba(255,79,24,0.1)',
      fill: true, tension: 0.4,
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

// Pie Chart
new Chart(document.getElementById('orderPie'), {
  type: 'doughnut',
  data: {
    labels: ['Delivered','Shipped','Processing','Pending','Cancelled'],
    datasets: [{ data: [60,15,12,8,5], backgroundColor: ['#22C55E','#3b82f6','#F59E0B','#A0A0B8','#EF4444'] }]
  },
  options: { plugins: { legend: { labels: { color: '#A0A0B8' } } } }
});

function updateOrderStatus(orderId, status) {
  fetch('../backend/api/update-order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: orderId, status: status })
  }).then(r => r.json()).then(d => {
    if (d.success) showToast('✅ Order status updated!', 'success');
  });
}
</script>
<script src="../js/script.js"></script>
</body>
</html>
