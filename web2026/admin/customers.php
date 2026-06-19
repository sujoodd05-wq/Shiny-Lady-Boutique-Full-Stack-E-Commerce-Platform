<?php
session_start();
require_once '../config/db.php';
global $conn;

$query = "SELECT u.*, 
          COUNT(o.id) as total_orders, 
          IFNULL(SUM(o.total_amount), 0) as total_spent 
          FROM users u 
          LEFT JOIN `orders` o ON u.full_name = o.customer_name 
          GROUP BY u.id 
          ORDER BY total_spent DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Directory | Shiny Lady Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .search-container { max-width: 350px; }
        .vip-badge {
            background-color: var(--china-doll);
            color: var(--plum-wine);
            font-weight: 600;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            border: 1px solid var(--copper-rose);
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
            <a href="customers.php" class="active"><i class="bi bi-people me-3"></i> Customers</a>
            <a href="analytics.php"><i class="bi bi-graph-up me-3"></i> Analytics</a>
            <a href="settings.php"><i class="bi bi-gear me-3"></i> Settings</a>

            <div style="margin-top: 100px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Are you sure you want to logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-plum-wine">Customer Directory</h2>

                <div class="search-container input-group shadow-sm">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="custSearch" class="form-control border-0 shadow-none" placeholder="Search by name or email...">
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                        <tr class="small text-muted">
                            <th class="ps-4">Customer Name</th>
                            <th>Email Address</th>
                            <th class="text-center">Phone Number</th>
                            <th class="text-center">Orders</th>
                            <th class="text-center">Total Spent</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                        </thead>
                        <tbody id="custTableBody">
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($user = mysqli_fetch_assoc($result)): ?>
                                <tr class="cust-row">
                                    <td class="ps-4">
                                        <div class="fw-bold text-plum-wine customer-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                        <?php if($user['total_spent'] >= 500): ?>
                                            <span class="vip-badge"><i class="bi bi-star-fill"></i> VIP</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="text-center">
                                        <span class="text-plum-wine"><?php echo !empty($user['phone']) ? $user['phone'] : '---'; ?></span>
                                    </td>
                                    <td class="text-center fw-bold"><?php echo $user['total_orders']; ?></td>
                                    <td class="text-center text-success fw-bold"><?php echo number_format($user['total_spent'], 2); ?> NIS</td>
                                    <td class="text-end pe-4">
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $user['email']; ?>"
                                           target="_blank"
                                           class="btn btn-sm btn-light border shadow-sm"
                                           title="Send Email via Gmail">
                                            <i class="bi bi-envelope-at text-plum-wine"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5">No registered customers yet.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>

    document.getElementById('custSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('.cust-row');
        rows.forEach(row => {
            let name = row.querySelector('.customer-name').innerText.toLowerCase();
            row.style.display = name.includes(value) ? '' : 'none';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>