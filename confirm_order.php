<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['order'])) {
    header('Location: order.php');
    exit();
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    foreach ($_SESSION['order'] as $order) {
        $product_id = $order['product_id'];
        $quantity = $order['quantity'];

        // Periksa stok saat ini
        $check_stock_sql = "SELECT stock FROM stock WHERE product_id = ?";
        $stmt = $conn->prepare($check_stock_sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_stock = $row['stock'];

        if ($current_stock < $quantity) {
            throw new Exception("Stok tidak cukup untuk produk: {$order['product_name']}");
        }

        // Kurangi stok
        $update_stock_sql = "UPDATE stock SET stock = stock - ? WHERE product_id = ?";
        $stmt = $conn->prepare($update_stock_sql);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        // Tambahkan transaksi
        $total_price = $order['total_price'];
        $order_type = $order['order_type'];
        $insert_transaction_sql = "INSERT INTO transactions (product_id, quantity, total_price, order_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_transaction_sql);
        $stmt->bind_param("iids", $product_id, $quantity, $total_price, $order_type);
        $stmt->execute();
    }

    // Commit transaksi
    mysqli_commit($conn);
    unset($_SESSION['order']);
    header('Location: order_success.php');
    exit();
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    mysqli_rollback($conn);
    echo "Terjadi kesalahan: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>