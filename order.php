<!DOCTYPE html>
<html>

<head>
    <title>Form Pesanan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Form Pesanan</h1>
    <nav>
        <a href="index.php">Daftar Produk</a> |
        <a href="order.php">Form Pesanan</a> |
        <a href="report.php">Laporan Penjualan</a> |
        <a href="stock_management.php">Manajemen Stok</a>
    </nav>


    <form action="save_order.php" method="post">

        <label for="product">Produk:</label>
        <select id="product" name="product" required>
            <?php
            include 'db_connect.php';
            $result = mysqli_query($conn, "SELECT * FROM products");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']} - {$row['type']} - Rp.{$row['price']}</option>";
            }
            ?>
        </select><br>
        <label for="quantity">Jumlah:</label>
        <input type="number" id="quantity" name="quantity" required><br>
        <label for="order_type">Tipe Pesanan:</label>
        <select id="order_type" name="order_type" required>
            <option value="Take Away">Take Away</option>
            <option value="Dine In">Dine In</option>
        </select><br>
        <input type="submit" value="Beli">

    </form>

    <?php
    session_start();
    if (isset($_SESSION['order'])) {
        echo "<h2>Daftar Pesanan</h2>";
        echo "<table><tr><th>Produk</th><th>Jumlah</th><th>Total Harga</th><th>Tipe Pesanan</th></tr>";
        foreach ($_SESSION['order'] as $order) {
            echo "<tr>";
            echo "<td>{$order['product_name']}</td>";
            echo "<td>{$order['quantity']}</td>";
            echo "<td>Rp.{$order['total_price']}</td>";
            echo "<td>{$order['order_type']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<form action='confirm_order.php' method='post'><input type='submit' value='Konfirmasi Pesanan'></form>";
    }
    ?>
</body>

</html>