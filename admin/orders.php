<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php"); exit;
}
$base = '../';
include '../backend/config/db.php';

$orders = $conn->query("SELECT o.*, u.name as user_name, u.email FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC");
$orders = $orders ? $orders->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Admin</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="sidebar-logo"><h3>⚙️ Admin Panel</h3><p><?= htmlspecialchars($_SESSION['user_name']) ?></p></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php"><span class="nav-icon">📊</span> Dashboard</a>
      <a href="orders.php" class="active"><span class="nav-icon">📦</span> Orders</a>
      <a href="products.php"><span class="nav-icon">🏷️</span> Products</a>
      <a href="users.php"><span class="nav-icon">👥</span> Users</a>
      <a href="../index.php"><span class="nav-icon">🏠</span> Go to Store</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>📦 All Orders</h1>
      <p><?= count($orders) ?> total orders</p>
    </div>

    <div class="data-table">
      <h3>Orders Management</h3>
      <table>
        <thead>
          <tr><th>ID</th><th>Customer</th><th>Email</th><th>Amount</th><th>Date</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
          <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px">No orders yet</td></tr>
          <?php else: ?>
          <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['user_name']) ?></td>
            <td style="color:var(--text-muted)"><?= htmlspecialchars($o['email']) ?></td>
            <td>₹<?= number_format($o['total_amount']) ?></td>
            <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
            <td>
              <form method="POST" action="../backend/api/update-order-status.php" style="display:inline">
                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                <select name="status" onchange="this.form.submit()"
                  style="background:var(--dark-4);border:1px solid var(--border);color:white;border-radius:6px;padding:4px 8px;font-size:12px;cursor:pointer">
                  <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="../js/script.js"></script>
</body>
</html>
