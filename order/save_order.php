<?php
session_start();
include '../db/db_connect.php';

// Mengambil ID produk, Jumlah, dan tipe pesanan dari form
$product_id = $_POST['product'];
$quantity = $_POST['quantity'];
$order_type = $_POST['order_type']; // Pastikan input name pada form sesuai

// Ambil detail produk
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$product_name = $product['name']; // nama produk yang dipilih
$product_price = $product['price']; // harga produk yang dipilih
$total_price = $product_price * $quantity; // Menghitung total harga

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

header('Location: order.php'); // Kembali ke form order
exit();
?>