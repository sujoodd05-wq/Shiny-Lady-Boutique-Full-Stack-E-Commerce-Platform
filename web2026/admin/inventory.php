<?php
require_once '../config/db.php';
global $conn;

$cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : null;

$query = "SELECT p.*, c.name as cat_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id";

if ($cat_id) {
    $query .= " WHERE p.category_id = $cat_id";
}

$query .= " ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
$total_skus = mysqli_num_rows($result);

$lowStockRes = mysqli_query($conn, "SELECT COUNT(*) as low_count FROM products WHERE stock <= 5 AND stock > 0");
$lowStockCount = mysqli_fetch_assoc($lowStockRes)['low_count'];

$outOfStockRes = mysqli_query($conn, "SELECT COUNT(*) as out_count FROM products WHERE stock = 0");
$outOfStockCount = mysqli_fetch_assoc($outOfStockRes)['out_count'];

$filter_name = "";
if ($cat_id) {
    $cat_res = mysqli_query($conn, "SELECT name FROM categories WHERE id = $cat_id");
    $cat_data = mysqli_fetch_assoc($cat_res);
    $filter_name = $cat_data['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management | Shiny Lady Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .stock-badge { padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .bg-healthy { background-color: #d1e7dd; color: #0f5132; } /* أخضر */
        .bg-low { background-color: #fff3cd; color: #664d03; border: 1px solid #ffe69c; } /* برتقالي */
        .bg-out { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; } /* أحمر */

        .inventory-img { width: 55px; height: 65px; object-fit: cover; border-radius: 8px; border: 1px solid var(--rosewater); }
        .stat-icon-box { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
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
            <a href="inventory.php" class="active"><i class="bi bi-box-seam me-3"></i> Inventory</a>
            <a href="categories.php"><i class="bi bi-tags me-3"></i> Categories</a>
            <a href="orders.php"><i class="bi bi-cart me-3"></i> Orders</a>
            <a href="customers.php"><i class="bi bi-people me-3"></i> Customers</a>
            <a href="analytics.php"><i class="bi bi-graph-up me-3"></i> Analytics</a>
            <a href="settings.php"><i class="bi bi-gear me-3"></i> Settings</a>

            <div style="margin-top: 100px;">
                <a href="login.php" onclick="return confirm('Logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-plum-wine">Inventory Assets</h2>
                <a href="add-product.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-2"></i> Add New Item</a>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card p-3 border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box bg-light text-plum-wine me-3"><i class="bi bi-box-seam"></i></div>
                            <div>
                                <small class="text-muted fw-bold small">TOTAL ITEMS</small>
                                <h4 class="fw-bold mb-0"><?php echo $total_skus; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box bg-warning-subtle text-warning me-3"><i class="bi bi-exclamation-triangle"></i></div>
                            <div>
                                <small class="text-muted fw-bold small">LOW STOCK</small>
                                <h4 class="fw-bold mb-0 text-warning"><?php echo $lowStockCount; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box bg-danger-subtle text-danger me-3"><i class="bi bi-x-octagon"></i></div>
                            <div>
                                <small class="text-muted fw-bold small">OUT OF STOCK</small>
                                <h4 class="fw-bold mb-0 text-danger"><?php echo $outOfStockCount; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($cat_id): ?>
                <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center" style="background: var(--china-doll); color: var(--plum-wine);">
                    <span>Currently filtering by: <b><?php echo $filter_name; ?></b></span>
                    <a href="inventory.php" class="btn btn-sm btn-outline-danger">Clear Filter</a>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Preview</th>
                            <th>Product Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th class="text-center">Stock Level</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)):
                            $stock = $row['stock'];
                            if($stock <= 0) {
                                $badgeClass = "bg-out";
                                $statusText = "Out of Stock";
                            } elseif($stock <= 5) {
                                $badgeClass = "bg-low";
                                $statusText = "Low Stock: $stock";
                            } else {
                                $badgeClass = "bg-healthy";
                                $statusText = "In Stock: $stock";
                            }
                            ?>
                            <tr class="item-row">
                                <td class="ps-4">
                                    <img src="../images/<?php echo $row['image']; ?>" class="inventory-img shadow-sm" onerror="this.src='https://via.placeholder.com/50x65'">
                                </td>
                                <td>
                                    <div class="fw-bold text-plum-wine item-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <small class="text-muted">ID: #<?php echo $row['id']; ?></small>
                                </td>
                                <td class="item-cat"><?php echo htmlspecialchars($row['cat_name']); ?></td>
                                <td class="fw-bold text-plum-wine"><?php echo $row['price']; ?> NIS</td>
                                <td class="text-center">
                                    <span class="stock-badge <?php echo $badgeClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border me-1"><i class="bi bi-pencil"></i></a>
                                    <a href="delete-product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>