<?php
session_start();
require_once '../config/db.php';
global $conn;

if (isset($_GET['update_id']) && isset($_GET['new_status'])) {
    $order_id = (int)$_GET['update_id'];
    $status = mysqli_real_escape_string($conn, $_GET['new_status']);
    mysqli_query($conn, "UPDATE `orders` SET status = '$status' WHERE id = $order_id");
    header("Location: orders.php?msg=StatusUpdated");
    exit();
}

$query = "SELECT * FROM `orders` ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Shiny Lady Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .status-select {
            font-size: 0.8rem;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid var(--rosewater);
            color: var(--plum-wine);
            background: #fff;
            cursor: pointer;
        }
        .search-container {
            max-width: 400px;
            background: #fff;
            border: 1px solid var(--rosewater);
            border-radius: 10px;
            padding: 5px 15px;
        }
        .btn-email { background-color: var(--copper-rose); color: white; border: none; }
        .btn-email:hover { background-color: var(--plum-wine); color: white; }
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

            <a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                <i class="bi bi-house-door me-3"></i> Dashboard
            </a>
            <a href="inventory.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'inventory.php') ? 'active' : ''; ?>">
                <i class="bi bi-box-seam me-3"></i> Inventory
            </a>
            <a href="categories.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'categories.php') ? 'active' : ''; ?>">
                <i class="bi bi-tags me-3"></i> Categories
            </a>
            <a href="orders.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'active' : ''; ?>">
                <i class="bi bi-cart-fill me-3"></i> Orders
            </a>
            <a href="customers.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'customers.php') ? 'active' : ''; ?>">
                <i class="bi bi-people me-3"></i> Customers
            </a>
            <a href="analytics.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'active' : ''; ?>">
                <i class="bi bi-graph-up me-3"></i> Analytics
            </a>
            <a href="settings.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
                <i class="bi bi-gear me-3"></i> Settings
            </a>

            <div style="margin-top: 50px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Confirm Logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-plum-wine">Customer Orders</h2>

                <div class="search-container d-flex align-items-center shadow-sm">
                    <i class="bi bi-search text-muted me-2"></i>
                    <input type="text" id="orderSearch" class="form-control border-0 shadow-none" placeholder="Search by customer name...">
                </div>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success py-2 small border-0 shadow-sm" style="background: var(--rosewater); color: var(--plum-wine);">
                    <i class="bi bi-check-circle me-2"></i> Order status has been updated.
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                        <tr class="small text-muted">
                            <th class="ps-4">Order ID</th>
                            <th>Customer Name</th>
                            <th>Total Amount</th>
                            <th>Current Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="orderTableBody">
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)):
                                $order_id = $row['id'];
                                $customer_name = $row['customer_name'];
                                $customer_email = $row['customer_email'];
                                $total = $row['total_amount'];
                                $status = $row['status'];

                                $subject = "Order #$order_id Confirmation";
                                $body = "Hello $customer_name ✨, We confirmed your order of $total NIS. 💖";
                                $mailto = "mailto:$customer_email?subject=".rawurlencode($subject)."&body=".rawurlencode($body);
                                ?>
                                <tr class="order-row">
                                    <td class="ps-4 fw-bold">#<?php echo $order_id; ?></td>
                                    <td>
                                        <div class="fw-bold text-plum-wine cust-name-text"><?php echo htmlspecialchars($customer_name); ?></div>
                                        <small class="text-muted small"><?php echo $customer_email; ?></small>
                                    </td>
                                    <td class="fw-bold"><?php echo $total; ?> NIS</td>
                                    <td>
                                        <select class="status-select" onchange="changeOrderStatus(<?php echo $order_id; ?>, this.value)">
                                            <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>🟡 Pending</option>
                                            <option value="Shipped" <?php if($status == 'Shipped') echo 'selected'; ?>>🔵 Shipped</option>
                                            <option value="Delivered" <?php if($status == 'Delivered') echo 'selected'; ?>>🟢 Delivered</option>
                                            <option value="Cancelled" <?php if($status == 'Cancelled') echo 'selected'; ?>>🔴 Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo $mailto; ?>" class="btn btn-sm btn-email me-2 shadow-sm" title="Contact via Email">
                                            <i class="bi bi-envelope-at"></i>
                                        </a>
                                        <a href="order-details.php?id=<?php echo $order_id; ?>" class="btn btn-sm btn-outline-secondary">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No orders found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.getElementById('orderSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('.order-row');
        rows.forEach(row => {
            let name = row.querySelector('.cust-name-text').innerText.toLowerCase();
            row.style.display = name.includes(value) ? '' : 'none';
        });
    });

    function changeOrderStatus(id, newStatus) {
        if(confirm('Update order #' + id + ' to ' + newStatus + '?')) {
            window.location.href = 'orders.php?update_id=' + id + '&new_status=' + newStatus;
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>