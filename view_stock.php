<!DOCTYPE html>
<html>
<head>
    <title>Lihat Stok</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Lihat Stok</h1>
    <nav>
        <a href="index.php">Tambah Stok</a> |
        <a href="view_stock.php">Lihat Stok</a>
    </nav>
    <h2>Stok Produk</h2>
    <table>
        <tr>
            <th>Produk</th>
            <th>Stok</th>
        </tr>
        <?php
        include 'db_connect.php';
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
