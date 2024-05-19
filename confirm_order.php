<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['order'])) {
    foreach ($_SESSION['order'] as $order) {
        $product_id = $order['product_id'];
        $quantity = $order['quantity'];
        $total_price = $order['total_price'];
        $order_type = $order['order_type'];
        
        $sql = "INSERT INTO transactions (product_id, quantity, total_price, order_type) VALUES ('$product_id', '$quantity', '$total_price', '$order_type')";
        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    // Menghapus sesi pesanan setelah disimpan
    unset($_SESSION['order']);
    echo "Pesanan berhasil dikonfirmasi.";
    header("Location:order.php");
} else {
    echo "Tidak ada pesanan yang perlu dikonfirmasi.";
    header("Location:order.php");
}

mysqli_close($conn);
?>
