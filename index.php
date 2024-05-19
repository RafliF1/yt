<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Produk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Manajemen Produk</h1>
    <nav>
        <a href="index.php">Daftar Produk</a> |
        <a href="order.php">Form Pesanan</a> |
        <a href="report.php">Laporan Penjualan</a> |
        <a href="stock_management.php">Manajemen Stok</a>
    </nav>

    <button onclick="location.href='add_product.php'">Tambah Produk</button>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Tipe</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php
        include "db_connect.php";
        $result = mysqli_query($conn, "SELECT * FROM products");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['type']}</td>";
            echo "<td>{$row['price']}</td>";
            echo "<td><a href='edit_product.php?id={$row['id']}'>Edit</a> | <a href='delete_product.php?id={$row['id']}'>Hapus</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>