<?php
header("Content-Type: application/json");
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id']);
$status = $conn->real_escape_string($data['status']);

if ($conn->query("UPDATE orders SET status='$status' WHERE id=$id")) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
