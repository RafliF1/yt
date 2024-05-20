<?php
session_start();
include 'db_connect.php';

$product_id = $_POST['product'];
$quantity = $_POST['quantity'];
$order_type = $_POST['order_type'];

// Ambil detail produk
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$product_name = $product['name'];
$product_price = $product['price'];
$total_price = $product_price * $quantity;

$order_item = [
    'product_id' => $product_id,
    'product_name' => $product_name,
    'quantity' => $quantity,
    'price_per_unit' => $product_price,
    'total_price' => $total_price,
    'order_type' => $order_type
];

if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = [];
}

$_SESSION['order'][] = $order_item;

$stmt->close();
$conn->close();

header('Location: order.php');
exit();
?>