<?php
include 'db_connect.php';

$product_id = $_POST['product'];
$quantity = $_POST['quantity'];
$order_type = $_POST['order_type'];

// Menyimpan pesanan di sesi
$order = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'quantity' => $quantity,
    'total_price' => $total_price,
    'order_type' => $order_type
);

// Mendapatkan harga produk
$result = mysqli_query($conn, "SELECT price FROM products WHERE id=$product_id");
$product = mysqli_fetch_assoc($result);
$price = $product['price'];
$total_price = $price * $quantity;

$sql = "INSERT INTO transactions (product_id, quantity, total_price, order_type) VALUES ('$product_id', '$quantity', '$total_price', '$order_type')";
if (mysqli_query($conn, $sql)) {
    echo "Pesanan berhasil ditambahkan.";
    header("Location:order.php");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = array();
}
$_SESSION['order'][] = $order;

header("Location:order.php");

mysqli_close($conn);
?>