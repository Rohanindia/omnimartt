<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$base = '../';
include '../backend/config/db.php';

$uid = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!empty($_POST['new_password'])) {
        if (!password_verify($_POST['current_password'], $user['password'])) {
            $error = 'Current password is incorrect.';
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = 'New passwords do not match.';
        } else {
            $hashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET name='".($conn->real_escape_string($name))."', phone='".($conn->real_escape_string($phone))."', address='".($conn->real_escape_string($address))."', password='$hashed' WHERE id=$uid");
            $success = 'Profile and password updated!';
        }
    } else {
        $conn->query("UPDATE users SET name='".($conn->real_escape_string($name))."', phone='".($conn->real_escape_string($phone))."', address='".($conn->real_escape_string($address))."' WHERE id=$uid");
        $_SESSION['user_name'] = $name;
        $success = 'Profile updated successfully!';
    }

    $user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="dashboard-layout">
  <aside class="sidebar">
    <div class="sidebar-logo"><h3>My Account</h3><p><?= htmlspecialchars($_SESSION['user_name']) ?></p></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php"><span class="nav-icon">📊</span> Dashboard</a>
      <a href="orders.php"><span class="nav-icon">📦</span> My Orders</a>
      <a href="profile.php" class="active"><span class="nav-icon">👤</span> Profile</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>👤 My Profile</h1>
      <p>Manage your account settings</p>
    </div>

    <?php if ($error): ?>
      <div class="error-msg" style="max-width:600px">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success-msg" style="max-width:600px">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" style="max-width:600px">
      <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;margin-bottom:20px">
        <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:20px">Personal Information</h3>
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity:0.6">
          <small style="color:var(--text-muted);font-size:12px">Email cannot be changed</small>
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+91 98765 43210">
        </div>
        <div class="form-group">
          <label>Default Address</label>
          <textarea name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>
      </div>

      <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;margin-bottom:20px">
        <h3 style="font-family:var(--font-display);font-size:18px;margin-bottom:4px">Change Password</h3>
        <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px">Leave blank to keep your current password</p>
        <div class="form-group">
          <label>Current Password</label>
          <input type="password" name="current_password" placeholder="Your current password">
        </div>
        <div class="form-group">
          <label>New Password</label>
          <input type="password" name="new_password" placeholder="Min. 6 characters">
        </div>
        <div class="form-group">
          <label>Confirm New Password</label>
          <input type="password" name="confirm_password" placeholder="Repeat new password">
        </div>
      </div>

      <button type="submit" class="form-submit" style="max-width:200px">Save Changes</button>
    </form>
  </main>
</div>

<script src="../js/script.js"></script>
</body>
</html>
