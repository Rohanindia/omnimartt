<?php
header("Content-Type: application/json");
include("../config/db.php");

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>