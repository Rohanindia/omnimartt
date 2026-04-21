<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit; }
$base = '../';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../backend/config/db.php';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') header("Location: ../admin/dashboard.php");
            elseif ($user['role'] === 'vendor') header("Location: ../vendor/dashboard.php");
            else header("Location: ../index.php");
            exit;
        } else {
            $error = 'Incorrect password. Please try again.';
        }
    } else {
        $error = 'No account found with that email.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="auth-page">
  <div class="auth-card">
    <h2>Welcome Back 👋</h2>
    <p class="subtitle">Sign in to your OmniMart account</p>

    <?php if ($error): ?>
      <div class="error-msg">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="form-submit">Sign In →</button>
    </form>

    <div class="auth-link">
      Don't have an account? <a href="register.php">Create one free</a>
    </div>

    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);text-align:center">
      Demo: admin@omnimart.com / password
    </div>
  </div>
</div>

<script src="../js/script.js"></script>
</body>
</html>
