<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$totalItems = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `orders` WHERE status = 'Pending'"))['total'];
$revenueRes = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM `orders` WHERE status != 'Cancelled'");
$revenueData = mysqli_fetch_assoc($revenueRes);
$totalRevenue = isset($revenueData['total']) ? $revenueData['total'] : 0;

$latestProducts = mysqli_query($conn, "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Shiny Lady</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .card-stat { background-color: var(--white); border: 1px solid var(--rosewater); border-radius: 12px; transition: 0.3s ease; }
        .card-stat:hover { box-shadow: 0 8px 25px rgba(117, 75, 77, 0.1); transform: translateY(-3px); }
        .clickable-stat { text-decoration: none; display: block; }
        .revenue-card { background-color: var(--china-doll); border-color: var(--copper-rose); }
        .search-input { border: 1px solid var(--rosewater); border-radius: 8px; padding: 8px 15px; outline: none; color: var(--plum-wine); width: 100%; max-width: 300px; }
        .search-input:focus { border-color: var(--plum-wine); }
        .quick-action-link { transition: 0.3s; border-radius: 8px !important; margin-bottom: 5px; border: none !important; }
        .quick-action-link:hover { background-color: var(--china-doll) !important; color: var(--plum-wine) !important; padding-left: 15px !important; }
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
            <a href="index.php" class="active"><i class="bi bi-house-door me-3"></i> Dashboard</a>
            <a href="inventory.php"><i class="bi bi-box-seam me-3"></i> Inventory</a>
            <a href="categories.php"><i class="bi bi-tags me-3"></i> Categories</a>
            <a href="orders.php"><i class="bi bi-cart me-3"></i> Orders</a>
            <a href="customers.php"><i class="bi bi-people me-3"></i> Customers</a>
            <a href="analytics.php"><i class="bi bi-graph-up me-3"></i> Analytics</a>
            <a href="settings.php"><i class="bi bi-gear me-3"></i> Settings</a>

            <div style="margin-top: 100px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Logout from Admin?') " class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-plum-wine">Store Overview</h2>
                    <p class="text-muted small">Welcome back, <b><?php echo $_SESSION['user_name']; ?></b></p>
                </div>
                <a href="add-product.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-circle me-1"></i> Add Product</a>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stat p-4 text-center">
                        <div class="text-muted small mb-1 fw-bold">TOTAL PRODUCTS</div>
                        <h2 class="fw-bold text-plum-wine mb-0"><?php echo $totalItems; ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <a href="analytics.php" class="clickable-stat">
                        <div class="card card-stat revenue-card p-4 text-center shadow-sm">
                            <div class="small mb-1 fw-bold" style="color: var(--plum-wine);">TOTAL REVENUE <i class="bi bi-graph-up-arrow ms-1"></i></div>
                            <h2 class="fw-bold mb-0" style="color: var(--copper-rose);"><?php echo number_format($totalRevenue, 2); ?> NIS</h2>
                            <span class="small text-muted mt-1" style="font-size: 0.65rem;">Click for analytics</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <div class="card card-stat p-4 text-center">
                        <div class="text-muted small mb-1 fw-bold">PENDING ORDERS</div>
                        <h2 class="fw-bold mb-0" style="color: var(--copper-rose);"><?php echo $pendingOrders; ?></h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 p-4" style="border-radius: 15px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-plum-wine mb-0">Recent Products</h5>
                            <input type="text" id="dashSearch" class="search-input shadow-none" placeholder="Filter by name...">
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                <tr class="small text-muted">
                                    <th>Preview</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                <?php while($row = mysqli_fetch_assoc($latestProducts)): ?>
                                    <tr class="product-row">
                                        <td><img src="../images/<?php echo $row['image']; ?>" class="rounded-2" width="45" height="50" style="object-fit: cover;" onerror="this.src='https://via.placeholder.com/45x50'"></td>
                                        <td class="fw-semibold text-plum-wine product-name"><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td class="small text-muted"><?php echo $row['cat_name']; ?></td>
                                        <td class="fw-bold"><?php echo $row['price']; ?> NIS</td>
                                        <td>
                                            <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-4 bg-white" style="border-radius: 15px;">
                        <h6 class="fw-bold text-plum-wine mb-3"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h6>
                        <hr class="text-rosewater mt-0">
                        <div class="list-group list-group-flush">
                            <a href="orders.php" class="list-group-item list-group-item-action quick-action-link small">
                                <i class="bi bi-cart-check me-2"></i> View All Orders
                            </a>
                            <a href="inventory.php" class="list-group-item list-group-item-action quick-action-link small">
                                <i class="bi bi-box-seam me-2"></i> Manage Inventory
                            </a>

                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="manage-admins.php" class="list-group-item list-group-item-action quick-action-link small text-danger fw-bold">
                                    <i class="bi bi-person-lock me-2"></i> Admin & Staff Control
                                </a>
                            <?php endif; ?>

                            <a href="settings.php" class="list-group-item list-group-item-action quick-action-link small">
                                <i class="bi bi-gear me-2"></i> Store Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    document.getElementById('dashSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            let name = row.querySelector('.product-name').innerText.toLowerCase();
            row.style.display = name.includes(filter) ? '' : 'none';
        });
    });
</script>

</body>
</html>