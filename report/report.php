<?php
include '../db/db_connect.php';

// Query untuk mengambil data penjualan yang dikelompokkan berdasarkan tanggal
$query = "SELECT DATE(order_date) as order_date, products.name, transactions.quantity, transactions.total_price, transactions.order_type 
          FROM transactions 
          INNER JOIN products ON transactions.product_id = products.id
          ORDER BY order_date";

$result = mysqli_query($conn, $query);

// Mengelompokkan data berdasarkan tanggal
$salesData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['order_date'];
    if (!isset($salesData[$date])) {
        $salesData[$date] = array();
    }
    $salesData[$date][] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" type="text/css" href="../css/reports.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Laporan Penjualan</h1>
    <nav>
        <a href="../index/index.php">Daftar Produk</a> |
        <a href="../order/order.php">Form Pesanan</a> |
        <a href="report.php">Laporan Penjualan</a> |
        <a href="../stock/stock_management.php">Manajemen Stok</a>
    </nav>

    <h2>Penjualan Harian</h2>
    <canvas id="dailySalesChart"></canvas>
    <h2>Penjualan Mingguan</h2>
    <canvas id="weeklySalesChart"></canvas>
    <h2>Penjualan Bulanan</h2>
    <canvas id="monthlySalesChart"></canvas>


    <h2>Laporan Penjualan Tabel</h2>
    <!-- Pilihan tampilan -->
    <label for="view">Pilih Tampilan:</label>
    <select id="view" name="view">
        <option value="simple">Tampilan Sederhana</option>
        <option value="detail">Detail Pesanan</option>
    </select>
    <button onclick="applyView()">Terapkan</button>

    <!-- Konten -->
    <div class="content">
        <?php
        // Menentukan tampilan yang dipilih
        $view = isset($_GET['view']) ? $_GET['view'] : 'simple';

        if ($view === 'simple') {
            // Tampilan sederhana
            echo "<h2>Laporan Penjualan (Tampilan Sederhana)</h2>";
            echo "<table>";
            echo "<tr><th>Tanggal</th><th>Total Penjualan</th></tr>";
            $simpleQuery = "SELECT DATE(order_date) AS order_date, SUM(total_price) AS total_sales FROM transactions GROUP BY DATE(order_date)";
            $simpleResult = mysqli_query($conn, $simpleQuery);
            while ($row = mysqli_fetch_assoc($simpleResult)) {
                echo "<tr>";
                echo "<td>{$row['order_date']}</td>";
                echo "<td>{$row['total_sales']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } elseif ($view === 'detail') {
            // Tampilan detail
            echo "<h2>Laporan Penjualan (Detail Pesanan)</h2>";
            echo "<table>";
            echo "<tr><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Total Harga</th><th>Tipe Pesanan</th>";

            foreach ($salesData as $date => $sales) {
                echo "<tr><td colspan='5'><strong>$date</strong></td></tr>";

                foreach ($sales as $sale) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>{$sale['name']}</td>";
                    echo "<td>{$sale['quantity']}</td>";
                    echo "<td>{$sale['total_price']}</td>";
                    echo "<td>{$sale['order_type']}</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
        ?>
    </div>

    <script>
    function applyView() {
        var view = document.getElementById("view").value;
        // Redirect to report.php with selected view mode
        window.location.href = "report.php?view=" + view;
    }
    </script>

    <script>
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');

    <?php
        // Data penjualan harian
        $dailySales = $conn->query("SELECT DATE(order_date) as date, SUM(total_price) as total FROM transactions GROUP BY DATE(order_date)");
        $dailyLabels = [];
        $dailyData = [];
        while ($row = $dailySales->fetch_assoc()) {
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
            borderColor: 'rgba(0, 0, 0, 1)',
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

    <?php
        // Data penjualan mingguan
        $weeklySales = $conn->query("SELECT WEEK(order_date) as week, SUM(total_price) as total FROM transactions GROUP BY WEEK(order_date)");
        $weeklyLabels = [];
        $weeklyData = [];
        while ($row = $weeklySales->fetch_assoc()) {
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
            borderColor: 'rgba(0, 0, 0, 1)',
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

    <?php
        // Data penjualan bulanan
        $monthlySales = $conn->query("SELECT MONTH(order_date) as month, SUM(total_price) as total FROM transactions GROUP BY MONTH(order_date)");
        $monthlyLabels = [];
        $monthlyData = [];
        while ($row = $monthlySales->fetch_assoc()) {
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
            borderColor: 'rgba(0, 0, 0, 1)',
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