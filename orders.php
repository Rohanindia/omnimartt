<?php
session_start();
include("backend/config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: frontend/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Omni Mart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">
        <a href="index.php" style="color:white;text-decoration:none;">
            Omni Mart
        </a>
    </div>
</header>

<section style="padding:40px 80px; background:#f1f3f6; min-height:100vh;">

    <h2 style="margin-bottom:30px;">My Orders</h2>

    <?php while($order = $orders->fetch_assoc()): ?>

        <div style="background:white; padding:25px; margin-bottom:20px; border-radius:8px; box-shadow:0 3px 10px rgba(0,0,0,0.08);">

            <div style="display:flex; justify-content:space-between;">
                <div>
                    <strong>Order ID:</strong> #<?php echo $order['id']; ?><br>
                    <strong>Date:</strong> <?php echo $order['created_at']; ?><br>
                    <strong>Total:</strong> ₹<?php echo number_format($order['total_amount'],2); ?>
                </div>

                <div>
                    <span style="background:#28a745; color:white; padding:6px 12px; border-radius:20px; font-size:12px;">
                        Delivered
                    </span>
                </div>
            </div>

            <hr style="margin:15px 0;">

            <strong>Items:</strong><br><br>

            <?php
            $order_id = $order['id'];
            $items = $conn->query("
                SELECT p.name, oi.quantity, oi.price 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = $order_id
            ");

            while($item = $items->fetch_assoc()):
            ?>

                <div style="margin-bottom:6px;">
                    • <?php echo $item['name']; ?> 
                    (<?php echo $item['quantity']; ?> x ₹<?php echo $item['price']; ?>)
                </div>

            <?php endwhile; ?>

        </div>

    <?php endwhile; ?>

</section>

</body>
</html>