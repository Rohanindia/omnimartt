<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../frontend/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Collect address fields
$address_parts = [
    $_POST['full_name'] ?? '',
    $_POST['address'] ?? '',
    $_POST['city'] ?? '',
    $_POST['state'] ?? '',
    $_POST['pincode'] ?? '',
    $_POST['phone'] ?? ''
];
$address = implode(', ', array_filter($address_parts));

// Fix: read payment_method correctly (was missing, now sent as hidden input)
$payment = $conn->real_escape_string($_POST['payment_method'] ?? 'COD');

// Decode cart data
$cartData = json_decode($_POST['cartData'] ?? '[]', true);

if (empty($cartData)) {
    // Cart is empty - redirect back to store
    header("Location: ../../index.php?error=empty_cart");
    exit;
}

// Calculate total from submitted cart
$total = 0;
foreach ($cartData as $item) {
    $total += floatval($item['price']) * intval($item['qty']);
}
$total = floatval($total);

// Insert order
$address_esc = $conn->real_escape_string($address);
$insertOrder = $conn->query(
    "INSERT INTO orders (user_id, total_amount, address, payment_method, status) 
     VALUES ($user_id, $total, '$address_esc', '$payment', 'pending')"
);

if (!$insertOrder) {
    // Order insert failed - redirect with error
    header("Location: ../../checkout.php?error=order_failed");
    exit;
}

$order_id = $conn->insert_id;

// Insert order items
foreach ($cartData as $item) {
    $product_id = intval($item['id']);
    $qty = intval($item['qty']);
    $price = floatval($item['price']);
    $conn->query(
        "INSERT INTO order_items (order_id, product_id, quantity, price) 
         VALUES ($order_id, $product_id, $qty, $price)"
    );
    // Reduce stock
    $conn->query("UPDATE products SET stock = GREATEST(0, stock - $qty) WHERE id=$product_id");
}

// Clear cart from localStorage and redirect to orders page with success
echo "<script>
localStorage.removeItem('omnimart_cart');
window.location.href='../../frontend/orders.php?success=1&order_id=$order_id';
</script>";
exit;
?>
