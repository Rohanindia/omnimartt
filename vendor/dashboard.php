<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../frontend/login.php"); exit;
}
$base = '../';
include '../backend/config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Dashboard - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="sidebar-logo"><h3>🏪 Vendor Panel</h3><p><?= htmlspecialchars($_SESSION['user_name']) ?></p></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="active"><span class="nav-icon">📊</span> Overview</a>
      <a href="#"><span class="nav-icon">🏷️</span> My Products</a>
      <a href="#"><span class="nav-icon">📦</span> Orders</a>
      <a href="#"><span class="nav-icon">💰</span> Earnings</a>
      <a href="../index.php"><span class="nav-icon">🏠</span> Go to Store</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>Vendor Dashboard 🏪</h1>
      <p>Manage your store and track your performance</p>
    </div>

    <div class="stats-grid">
      <div class="stat-card"><div class="stat-card-icon">💰</div><h3>Monthly Revenue</h3><div class="value">₹42,000</div><div class="change">↑ 12% vs last month</div></div>
      <div class="stat-card"><div class="stat-card-icon">📦</div><h3>Total Orders</h3><div class="value">128</div><div class="change">↑ 8 new today</div></div>
      <div class="stat-card"><div class="stat-card-icon">🏷️</div><h3>Products Listed</h3><div class="value">24</div><div class="change">Active listings</div></div>
      <div class="stat-card"><div class="stat-card-icon">⭐</div><h3>Avg. Rating</h3><div class="value">4.7</div><div class="change">Based on 340 reviews</div></div>
    </div>

    <div class="charts-grid">
      <div class="chart-card">
        <h3>Sales Performance</h3>
        <canvas id="vendorChart" height="100"></canvas>
      </div>
      <div class="chart-card">
        <h3>Top Categories</h3>
        <canvas id="catChart"></canvas>
      </div>
    </div>
  </main>
</div>

<script>
new Chart(document.getElementById('vendorChart'), {
  type: 'bar',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun'],
    datasets: [{ label: 'Sales (₹)', data: [18000,24000,20000,32000,28000,42000], backgroundColor: '#FF4F18' }]
  },
  options: {
    plugins: { legend: { labels: { color: '#A0A0B8' } } },
    scales: {
      x: { ticks: { color: '#606078' }, grid: { color: 'rgba(255,255,255,0.05)' } },
      y: { ticks: { color: '#606078' }, grid: { color: 'rgba(255,255,255,0.05)' } }
    }
  }
});
new Chart(document.getElementById('catChart'), {
  type: 'doughnut',
  data: {
    labels: ['Electronics','Fashion','Home'],
    datasets: [{ data: [55,30,15], backgroundColor: ['#FF4F18','#FF9A00','#22C55E'] }]
  },
  options: { plugins: { legend: { labels: { color: '#A0A0B8' } } } }
});
</script>
<script src="../js/script.js"></script>
</body>
</html>
