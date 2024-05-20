<!DOCTYPE html>
<html>

<head>
    <title>Daftar Produk</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <h1>Daftar Produk</h1>

    <!-- Navigasi ke halaman lain -->
    <nav>
        <a href="index.php">Daftar Produk</a>|
        <a href="../order/order.php"> Form Pesanan</a>|
        <a href="../report/report.php"> Laporan Penjualan</a>|
        <a href="../stock/stock_management.php"> Manajemen Stok</a>
    </nav>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Jenis</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Menghubungkan ke database, Mengambil data produk dari tabel, Menampilkan data produk dalam tabel
        include '../db/db_connect.php'; 
        $result = $conn->query("SELECT * FROM products"); 
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['type']}</td>";
            echo "<td>Rp.{$row['price']}</td>";
            echo "<td>
                <a href='../product/edit_product.php?id={$row['id']}'>Edit</a> |
                <a href='../product/delete_product.php?id={$row['id']}'>Hapus</a>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    <a href="../product/add_product.php" class="add-button">Tambah Produk</a>
</body>

</html>