<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php"); exit;
}
$base = '../';
include '../backend/config/db.php';

$users = $conn->query("SELECT u.*, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON u.id=o.user_id GROUP BY u.id ORDER BY u.created_at DESC");
$users = $users ? $users->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users - Admin</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="sidebar-logo"><h3>⚙️ Admin Panel</h3><p><?= htmlspecialchars($_SESSION['user_name']) ?></p></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php"><span class="nav-icon">📊</span> Dashboard</a>
      <a href="orders.php"><span class="nav-icon">📦</span> Orders</a>
      <a href="products.php"><span class="nav-icon">🏷️</span> Products</a>
      <a href="users.php" class="active"><span class="nav-icon">👥</span> Users</a>
      <a href="../index.php"><span class="nav-icon">🏠</span> Go to Store</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>👥 Users</h1>
      <p><?= count($users) ?> registered users</p>
    </div>

    <div class="data-table">
      <h3>All Users</h3>
      <table>
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Orders</th><th>Joined</th></tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr>
            <td>#<?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td style="color:var(--text-muted)"><?= htmlspecialchars($u['email']) ?></td>
            <td>
              <span class="status-badge <?= $u['role'] === 'admin' ? 'status-delivered' : ($u['role'] === 'vendor' ? 'status-processing' : 'status-pending') ?>">
                <?= ucfirst($u['role']) ?>
              </span>
            </td>
            <td><?= $u['order_count'] ?></td>
            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="../js/script.js"></script>
</body>
</html>
