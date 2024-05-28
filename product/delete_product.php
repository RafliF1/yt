<?php
include '../db/db_connect.php';

// Pastikan parameter id tersedia
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Mulai transaksi
    $conn->begin_transaction();

    // Hapus entri terkait di tabel transaksi
    $sql_delete_transactions = "DELETE FROM transactions WHERE product_id = ?";
    $stmt_delete_transactions = $conn->prepare($sql_delete_transactions);
    $stmt_delete_transactions->bind_param("i", $product_id);
    $stmt_delete_transactions->execute();
    $stmt_delete_transactions->close();

    // Hapus entri terkait di tabel stok
    $sql_delete_stock = "DELETE FROM stock WHERE product_id = ?";
    $stmt_delete_stock = $conn->prepare($sql_delete_stock);
    $stmt_delete_stock->bind_param("i", $product_id);
    $stmt_delete_stock->execute();
    $stmt_delete_stock->close();

    // Hapus produk dari tabel products
    $sql_delete_product = "DELETE FROM products WHERE id = ?";
    $stmt_delete_product = $conn->prepare($sql_delete_product);
    $stmt_delete_product->bind_param("i", $product_id);
    $stmt_delete_product->execute();
    $stmt_delete_product->close();

    // Lakukan commit transaksi jika semua perintah berhasil
    $conn->commit();

    // Tutup koneksi
    $conn->close();

    // Kembalikan pengguna ke halaman sebelumnya
    header('Location: ../index/index.php');
    exit();
} else {
    echo "Product ID is required.";
}
?>