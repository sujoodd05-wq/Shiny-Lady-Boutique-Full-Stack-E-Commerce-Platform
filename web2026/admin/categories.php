<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST['add_cat'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);

    if(mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')")) {
        $admin_name = $_SESSION['user_name'];
        $log_details = "Created a new category: " . $name;
        mysqli_query($conn, "INSERT INTO activity_log (admin_id, admin_name, action, details) 
                             VALUES ('{$_SESSION['admin_id']}', '$admin_name', 'Categories', '$log_details')");

        header("Location: categories.php?msg=added");
        exit();
    }
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $cat_res = mysqli_query($conn, "SELECT name FROM categories WHERE id = $id");
    $cat_data = mysqli_fetch_assoc($cat_res);

    if($cat_data) {
        $cat_name = $cat_data['name'];
        if(mysqli_query($conn, "DELETE FROM categories WHERE id = $id")) {
            $admin_name = $_SESSION['user_name'];
            $log_details = "Deleted category: " . $cat_name;
            mysqli_query($conn, "INSERT INTO activity_log (admin_id, admin_name, action, details) 
                                 VALUES ('{$_SESSION['admin_id']}', '$admin_name', 'Categories', '$log_details')");

            header("Location: categories.php?msg=deleted");
            exit();
        }
    }
}

$categories = mysqli_query($conn, "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as item_count FROM categories c ORDER BY c.id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | Shiny Lady Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .category-link { color: var(--plum-wine); text-decoration: none; font-weight: 600; transition: 0.3s; display: flex; align-items: center; gap: 10px; }
        .category-link:hover { color: var(--copper-rose); padding-left: 5px; }
        .badge-count { background-color: var(--china-doll); color: var(--plum-wine); border: 1px solid var(--rosewater); font-weight: 600; }
        .form-control:focus { border-color: var(--plum-wine); box-shadow: none; }
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

            <div style="margin-top: 100px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <h2 class="fw-bold text-plum-wine mb-4">Shop Categories</h2>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success border-0 shadow-sm py-2 px-4 mb-4 small" style="background-color: var(--rosewater); color: var(--plum-wine);">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Category was successfully <?php echo htmlspecialchars($_GET['msg']); ?>.
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                        <h5 class="fw-bold text-plum-wine mb-3"><i class="bi bi-plus-circle me-2"></i>New Category</h5>
                        <form action="categories.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Category Name</label>
                                <input type="text" name="cat_name" class="form-control shadow-none" placeholder="e.g. Rings" style="border-color: var(--rosewater); padding: 12px;" required>
                            </div>
                            <button type="submit" name="add_cat" class="btn btn-primary w-100 shadow-sm py-2">Create Category</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-plum-wine">Available Sections</h5>
                            <span class="small text-muted">Click name to filter inventory</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                <tr class="small text-muted">
                                    <th class="ps-4">ID</th>
                                    <th>Category Name</th>
                                    <th class="text-center">Items Count</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($row = mysqli_fetch_assoc($categories)): ?>
                                    <tr>
                                        <td class="ps-4 text-muted">#<?php echo $row['id']; ?></td>
                                        <td>
                                            <a href="inventory.php?cat_id=<?php echo $row['id']; ?>" class="category-link" title="Filter Inventory">
                                                <?php echo htmlspecialchars($row['name']); ?>
                                                <i class="bi bi-arrow-up-right-circle small opacity-50"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill px-3 badge-count">
                                                <?php echo $row['item_count']; ?> Items
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="categories.php?delete=<?php echo $row['id']; ?>"
                                               class="btn btn-sm btn-light border text-danger shadow-sm"
                                               onclick="return confirm('⚠️ Warning: Deleting this category might affect product display. Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>