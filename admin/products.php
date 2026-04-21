<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.php"); exit;
}
$base = '../';
include '../backend/config/db.php';

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete'])) {
    $did = intval($_GET['delete']);
    if ($conn->query("DELETE FROM products WHERE id=$did")) {
        $success = 'Product deleted.';
    }
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $original_price = floatval($_POST['original_price']);
    $category = $conn->real_escape_string($_POST['category']);
    $brand = $conn->real_escape_string($_POST['brand']);
    $image = $conn->real_escape_string($_POST['image']);
    $stock = intval($_POST['stock']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    if (isset($_POST['product_id']) && $_POST['product_id']) {
        $pid = intval($_POST['product_id']);
        $conn->query("UPDATE products SET name='$name', description='$description', price=$price, original_price=$original_price, category='$category', brand='$brand', image='$image', stock=$stock, is_featured=$is_featured WHERE id=$pid");
        $success = 'Product updated!';
    } else {
        $conn->query("INSERT INTO products (name, description, price, original_price, category, brand, image, stock, is_featured) VALUES ('$name','$description',$price,$original_price,'$category','$brand','$image',$stock,$is_featured)");
        $success = 'Product added!';
    }
}

$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $products ? $products->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Admin</title>
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
      <a href="products.php" class="active"><span class="nav-icon">🏷️</span> Products</a>
      <a href="users.php"><span class="nav-icon">👥</span> Users</a>
      <a href="../index.php"><span class="nav-icon">🏠</span> Go to Store</a>
      <a href="../backend/api/logout.php" style="color:var(--danger)"><span class="nav-icon">🚪</span> Logout</a>
    </nav>
  </aside>

  <main class="dashboard-content">
    <div class="dashboard-header">
      <h1>🏷️ Products</h1>
      <p>Manage your product catalog</p>
    </div>

    <?php if ($success): ?><div class="success-msg">✅ <?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error-msg">⚠️ <?= $error ?></div><?php endif; ?>

    <!-- ADD PRODUCT FORM -->
    <div style="background:var(--dark-3);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:28px">
      <h3 style="font-family:var(--font-display);margin-bottom:20px">➕ Add New Product</h3>
      <form method="POST">
        <input type="hidden" name="product_id" value="">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
          <div class="form-group"><label>Product Name</label><input type="text" name="name" required></div>
          <div class="form-group"><label>Brand</label><input type="text" name="brand"></div>
          <div class="form-group">
            <label>Category</label>
            <select name="category">
              <option value="electronics">Electronics</option>
              <option value="fashion">Fashion</option>
              <option value="home">Home & Living</option>
              <option value="sports">Sports</option>
              <option value="books">Books</option>
              <option value="beauty">Beauty</option>
            </select>
          </div>
          <div class="form-group"><label>Price (₹)</label><input type="number" name="price" required></div>
          <div class="form-group"><label>Original Price (₹)</label><input type="number" name="original_price"></div>
          <div class="form-group"><label>Stock</label><input type="number" name="stock" value="0"></div>
        </div>
        <div class="form-group"><label>Image URL (Unsplash or direct link)</label><input type="text" name="image" placeholder="https://images.unsplash.com/..."></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="2"></textarea></div>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
          <input type="checkbox" name="is_featured" id="feat" style="accent-color:var(--primary);width:16px;height:16px">
          <label for="feat">Mark as Featured Product</label>
        </div>
        <button type="submit" class="form-submit" style="max-width:180px">Add Product</button>
      </form>
    </div>

    <!-- PRODUCTS TABLE -->
    <div class="data-table">
      <h3>All Products (<?= count($products) ?>)</h3>
      <table>
        <thead>
          <tr>
            <th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Featured</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
          <tr>
            <td><img src="<?= htmlspecialchars($p['image']) ?>" style="width:44px;height:44px;border-radius:6px;object-fit:cover" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=100&q=80'"></td>
            <td style="font-weight:500"><?= htmlspecialchars($p['name']) ?></td>
            <td><?= ucfirst($p['category']) ?></td>
            <td>₹<?= number_format($p['price']) ?></td>
            <td><?= $p['stock'] ?></td>
            <td><?= $p['is_featured'] ? '⭐' : '-' ?></td>
            <td style="display:flex;gap:8px">
              <a href="../product.php?id=<?= $p['id'] ?>" style="color:var(--primary);font-size:13px" target="_blank">View</a>
              <a href="?delete=<?= $p['id'] ?>" style="color:var(--danger);font-size:13px" 
                onclick="return confirm('Delete this product?')">Delete</a>
            </td>
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
