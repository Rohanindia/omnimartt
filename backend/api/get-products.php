<?php
header("Content-Type: application/json");
include("../config/db.php");

$sql = "SELECT * FROM products WHERE stock > 0 ORDER BY created_at DESC";
$result = $conn->query($sql);
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
echo json_encode($products);
?>
