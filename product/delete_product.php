<?php
// Menghubungkan ke database
include '../db/db_connect.php';

// Memeriksa apakah parameter id telah disertakan dalam permintaan
if (isset($_GET['id'])) {
    // Memastikan bahwa id produk yang akan dihapus adalah angka
    if (is_numeric($_GET['id'])) {
        // Mendapatkan ID produk yang akan dihapus
        $productId = $_GET['id'];

        // Query untuk menghapus produk dari database
        $deleteQuery = "DELETE FROM products WHERE id = $productId";

        // Eksekusi query penghapusan
        if ($conn->query($deleteQuery) === TRUE) {
            echo "Produk berhasil dihapus.";
        } else {
            echo "Error: " . $deleteQuery . "<br>" . $conn->error;
        }

        // Menutup koneksi database
        $conn->close();
    } else {
        // ID produk tidak valid (bukan angka)
        echo "ID produk tidak valid.";
    }
} else {
    // Parameter id tidak ada dalam permintaan
    echo "Parameter id tidak ditemukan dalam permintaan.";
}
?>