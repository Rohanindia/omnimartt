<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit; }
$base = '../';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../backend/config/db.php';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (strlen($name) < 2) {
        $error = 'Name must be at least 2 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $name, $email, $hashed);
            if ($stmt2->execute()) {
                $success = 'Account created! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - OmniMart</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="auth-page">
  <div class="auth-card">
    <h2>Create Account 🚀</h2>
    <p class="subtitle">Join OmniMart and start shopping</p>

    <?php if ($error): ?>
      <div class="error-msg">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success-msg">✅ <?= htmlspecialchars($success) ?> <a href="login.php">Login now</a></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your full name" required
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min. 6 characters" required>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Repeat your password" required>
      </div>
      <button type="submit" class="form-submit">Create Account →</button>
    </form>

    <div class="auth-link">
      Already have an account? <a href="login.php">Sign in</a>
    </div>
  </div>
</div>

<script src="../js/script.js"></script>
</body>
</html>
