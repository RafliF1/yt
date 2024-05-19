<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Stok</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Manajemen Stok</h1>
    <nav>
        <a href="index.php">Daftar Produk</a> |
        <a href="order.php">Form Pesanan</a> |
        <a href="report.php">Laporan Penjualan</a> |
        <a href="stock_management.php">Manajemen Stok</a>
    </nav>
    <h2>Tambah Stok</h2>
    <form action="add_stock.php" method="post">
        <label for="product">Produk:</label>
        <select id="product" name="product" required>
            <?php
            include 'db_connect.php';
            $result = mysqli_query($conn, "SELECT * FROM products");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select><br>
        <label for="quantity">Jumlah:</label>
        <input type="number" id="quantity" name="quantity" required><br>
        <input type="submit" value="Tambah Stok">
    </form>
    <h2>Stok Produk</h2>
    <table>
        <tr>
            <th>Produk</th>
            <th>Stok</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT p.name AS product_name, s.stock FROM products p INNER JOIN stock s ON p.id = s.product_id");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['product_name']}</td>";
            echo "<td>{$row['stock']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>