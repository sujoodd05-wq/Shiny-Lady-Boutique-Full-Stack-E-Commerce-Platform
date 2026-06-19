<?php
session_start();
require_once '../config/db.php';
global $conn;

$salesTrend = mysqli_query($conn, "SELECT DATE(created_at) as date, SUM(total_amount) as total 
                                   FROM `orders` 
                                   GROUP BY DATE(created_at) 
                                   ORDER BY date ASC LIMIT 7");
$dates = [];
$totals = [];
while($row = mysqli_fetch_assoc($salesTrend)) {
    $dates[] = $row['date'];
    $totals[] = $row['total'];
}

$allOrders = mysqli_query($conn, "SELECT order_items FROM `orders` ");
$productCounts = [];

while($order = mysqli_fetch_assoc($allOrders)) {
    $items = json_decode($order['order_items'], true);
    if($items) {
        foreach($items as $item) {
            $name = $item['name'];
            $qty = (int)$item['qty'];
            if(isset($productCounts[$name])) {
                $productCounts[$name] += $qty;
            } else {
                $productCounts[$name] = $qty;
            }
        }
    }
}
arsort($productCounts);
$topProducts = array_slice($productCounts, 0, 5);
$productNames = array_keys($topProducts);
$productQty = array_values($topProducts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analytics | Shiny Lady</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .badge-units {
            background-color: var(--china-doll);
            color: var(--plum-wine);
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 50px;
            border: 1px solid var(--rosewater);
            font-size: 0.95rem;
            display: inline-block;
        }

        .table thead th {
            background-color: #fcfaf9;
            color: var(--plum-wine);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            padding: 15px;
            border-bottom: 2px solid var(--rosewater);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .analytics-card {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(117, 75, 77, 0.05);
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row flex-column flex-md-row">

        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="text-center mb-5">
                <h4 class="fw-bold mt-4 text-white" style="letter-spacing: 3px;">SHINY LADY</h4>
                <hr style="color: var(--rosewater); opacity: 0.5;">
            </div>

            <a href="index.php"><i class="bi bi-house-door me-3"></i> Dashboard</a>
            <a href="inventory.php"><i class="bi bi-box-seam me-3"></i> Inventory</a>
            <a href="categories.php"><i class="bi bi-tags me-3"></i> Categories</a>
            <a href="orders.php"><i class="bi bi-cart me-3"></i> Orders</a>
            <a href="customers.php"><i class="bi bi-people me-3"></i> Customers</a>
            <a href="analytics.php" class="active"><i class="bi bi-graph-up me-3"></i> Analytics</a>
            <a href="settings.php"><i class="bi bi-gear me-3"></i> Settings</a>

            <div style="margin-top: 100px;">
                <a href="login.php" onclick="return confirm('Logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-plum-wine">Detailed Analytics</h2>
                <button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="bi bi-printer me-2"></i> Print Report</button>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card analytics-card p-4 h-100">
                        <h5 class="fw-bold text-plum-wine mb-4"><i class="bi bi-calendar-check me-2"></i>Sales Trend (Last 7 Days)</h5>
                        <canvas id="salesLineChart" height="150"></canvas>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card analytics-card p-4 h-100">
                        <h5 class="fw-bold text-plum-wine mb-4"><i class="bi bi-pie-chart me-2"></i>Best Sellers</h5>
                        <canvas id="topProductsPieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card analytics-card p-4">
                <h5 class="fw-bold text-plum-wine mb-4"><i class="bi bi-list-stars me-2"></i>Product Performance Summary</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th class="ps-4">Product Name</th>
                            <th class="text-center">Units Sold</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($topProducts as $name => $qty): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-plum-wine" style="font-size: 1.1rem;"><?php echo $name; ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="badge-units"><?php echo $qty; ?> Units Sold</div>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="text-success small fw-bold"><i class="bi bi-trend-up"></i> Top Selling Piece</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const boutiquePalette = ['#754B4D', '#A86A65', '#D8A694', '#AB8882', '#E0CBB9'];

    const lineCtx = document.getElementById('salesLineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Sales (NIS)',
                data: <?php echo json_encode($totals); ?>,
                borderColor: '#A86A65',
                backgroundColor: 'rgba(168, 106, 101, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#754B4D'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    const pieCtx = document.getElementById('topProductsPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($productNames); ?>,
            datasets: [{
                data: <?php echo json_encode($productQty); ?>,
                backgroundColor: boutiquePalette,
                hoverOffset: 10,
                borderWidth: 3,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, font: { size: 12 } } }
            },
            cutout: '60%'
        }
    });
</script>

</body>
</html>