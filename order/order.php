<!DOCTYPE html>
<html>

<head>
    <title>Form Pesanan</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <h1>Form Pesanan</h1>
    <nav>
        <a href="../index/index.php">Daftar Produk</a>|
        <a href="order.php"> Form Pesanan</a>|
        <a href="../report/report.php"> Laporan Penjualan</a>|
        <a href="../stock/stock_management.php"> Manajemen Stok</a>
    </nav>


    <?php
    if (isset($_GET['error'])) {
        echo "<p class='error-message'>{$_GET['error']}</p>";
    }
    ?>

    <form action="save_order.php" method="post">
        <label for="product">Produk:</label>
        <select id="product" name="product" required>
            <?php
            include '../db/db_connect.php';
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
        <input type="submit" value="Tambah">
    </form>

    <?php
    session_start();
    if (isset($_SESSION['order'])) {
        echo "<h2>Daftar Pesanan</h2>";
        echo "<table><tr><th>Produk</th><th>Jumlah</th><th>Total Harga</th><th>Tipe Pesanan</th><th>Aksi</th></tr>";
        foreach ($_SESSION['order'] as $index => $order) {
            echo "<tr>";
            echo "<td>{$order['product_name']}</td>";
            echo "<td>{$order['quantity']}</td>";
            echo "<td>Rp." . number_format($order['total_price'], 2, ',', '.') . "</td>";
            echo "<td>{$order['order_type']}</td>";
            echo "<td><a href='edit_order.php?action=reduce&index={$index}'>Kurangi</a> | <a href='edit_order.php?action=remove&index={$index}'>Hapus</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<form action='confirm_order.php' method='post'><input type='submit' value='Konfirmasi Pesanan'></form>";
    }
    ?>
</body>

</html>