<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Laporan Penjualan</h1>
    <nav>
        <a href="index.php">Daftar Produk</a> |
        <a href="order.php">Form Pesanan</a> |
        <a href="report.php">Laporan Penjualan</a> |
        <a href="stock_management.php">Manajemen Stok</a>
    </nav>
    <h2>Penjualan Harian</h2>
    <canvas id="dailySalesChart"></canvas>
    <h2>Penjualan Mingguan</h2>
    <canvas id="weeklySalesChart"></canvas>
    <h2>Penjualan Bulanan</h2>
    <canvas id="monthlySalesChart"></canvas>
    <h2>Laporan Penjualan Tabel</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
            <th>Tipe Pesanan</th>
        </tr>
        <?php
        include 'db_connect.php';
        
        // Query untuk mengambil data transaksi
        $result = mysqli_query($conn, "SELECT * FROM transactions");
        $total_penjualan = 0;
        
        // Loop untuk menampilkan data transaksi dalam tabel
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['order_date']}</td>";
            
            // Ambil nama produk berdasarkan product_id
            $product_result = mysqli_query($conn, "SELECT name FROM products WHERE id={$row['product_id']}");
            $product_name = mysqli_fetch_assoc($product_result)['name'];
            echo "<td>{$product_name}</td>";
            
            echo "<td>{$row['quantity']}</td>";
            echo "<td>Rp." . number_format($row['total_price'], 2, ',', '.') . "</td>";
            echo "<td>{$row['order_type']}</td>";
            echo "</tr>";

            // Hitung total penjualan
            $total_penjualan += $row['total_price'];
        }
        ?>
    </table>
    <h3>Total Penjualan: Rp.<?php echo number_format($total_penjualan, 2, ',', '.'); ?></h3>

    <script>
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');

    // Data penjualan harian
    <?php
        include 'db_connect.php';

        $dailySales = mysqli_query($conn, "
            SELECT DATE(order_date) as date, SUM(total_price) as total
            FROM transactions
            GROUP BY DATE(order_date)
        ");
        $dailyLabels = [];
        $dailyData = [];
        while ($row = mysqli_fetch_assoc($dailySales)) {
            $dailyLabels[] = $row['date'];
            $dailyData[] = $row['total'];
        }
    ?>

    const dailySalesData = {
        labels: <?php echo json_encode($dailyLabels); ?>,
        datasets: [{
            label: 'Penjualan Harian',
            data: <?php echo json_encode($dailyData); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    const dailySalesChart = new Chart(dailySalesCtx, {
        type: 'line',
        data: dailySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Data penjualan mingguan
    <?php
        $weeklySales = mysqli_query($conn, "
            SELECT WEEK(order_date) as week, SUM(total_price) as total
            FROM transactions
            GROUP BY WEEK(order_date)
        ");
        $weeklyLabels = [];
        $weeklyData = [];
        while ($row = mysqli_fetch_assoc($weeklySales)) {
            $weeklyLabels[] = 'Week ' . $row['week'];
            $weeklyData[] = $row['total'];
        }
    ?>

    const weeklySalesData = {
        labels: <?php echo json_encode($weeklyLabels); ?>,
        datasets: [{
            label: 'Penjualan Mingguan',
            data: <?php echo json_encode($weeklyData); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    const weeklySalesChart = new Chart(weeklySalesCtx, {
        type: 'bar',
        data: weeklySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Data penjualan bulanan
    <?php
        $monthlySales = mysqli_query($conn, "
            SELECT MONTH(order_date) as month, SUM(total_price) as total
            FROM transactions
            GROUP BY MONTH(order_date)
        ");
        $monthlyLabels = [];
        $monthlyData = [];
        while ($row = mysqli_fetch_assoc($monthlySales)) {
            $monthlyLabels[] = 'Month ' . $row['month'];
            $monthlyData[] = $row['total'];
        }
    ?>

    const monthlySalesData = {
        labels: <?php echo json_encode($monthlyLabels); ?>,
        datasets: [{
            label: 'Penjualan Bulanan',
            data: <?php echo json_encode($monthlyData); ?>,
            backgroundColor: 'rgba(255, 0, 0, 0.5)',
            borderColor: 'rgba(255, 0, 0, 1)',
            borderWidth: 1
        }]
    };

    const monthlySalesChart = new Chart(monthlySalesCtx, {
        type: 'bar',
        data: monthlySalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>

</html>