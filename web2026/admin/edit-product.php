<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($res);

    if (!$product) {
        die("Product not found!");
    }
} else {
    header("Location: inventory.php");
    exit();
}

if (isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $cat_id = $_POST['category_id'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_name = $product['image'];

    if (!empty($_FILES['product_image']['name'])) {
        $image_name = $_FILES['product_image']['name'];
        $temp_name = $_FILES['product_image']['tmp_name'];
        move_uploaded_file($temp_name, "../images/" . $image_name);
    }

    $update_sql = "UPDATE products SET 
                   name = '$name', 
                   price = '$price', 
                   stock = '$stock', 
                   category_id = '$cat_id', 
                   image = '$image_name', 
                   description = '$description' 
                   WHERE id = $id";

    if (mysqli_query($conn, $update_sql)) {
        $admin_name = $_SESSION['user_name'];
        $log_details = "Updated product: $name (New Price: $price NIS, New Stock: $stock)";
        mysqli_query($conn, "INSERT INTO activity_log (admin_id, admin_name, action, details) 
                             VALUES ('{$_SESSION['admin_id']}', '$admin_name', 'Inventory', '$log_details')");

        echo "<script>alert('Product updated successfully! ✨'); window.location.href='inventory.php';</script>";
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Accessory | Shiny Lady Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
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

            <div style="margin-top: 50px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Are you sure?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex align-items-center mb-4">
                <a href="inventory.php" class="btn btn-sm btn-light me-3 border shadow-sm text-plum-wine"><i class="bi bi-arrow-left"></i></a>
                <h2 class="fw-bold text-plum-wine mb-0">Edit Item #<?php echo $id; ?></h2>
            </div>

            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <p class="text-muted small fw-bold text-uppercase">Product Media</p>
                            <img src="../images/<?php echo $product['image']; ?>" class="img-fluid rounded-3 mb-3 shadow-sm" style="max-height: 280px; object-fit: cover; border: 1px solid #eee;">
                            <div class="mt-2 text-start">
                                <label class="form-label small fw-bold">Update Image</label>
                                <input type="file" name="product_image" class="form-control form-control-sm shadow-none">
                                <small class="text-muted" style="font-size: 0.7rem;">Leave empty to keep current photo.</small>
                            </div>
                        </div>

                        <div class="col-md-8 px-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Product Name</label>
                                <input type="text" name="name" class="form-control shadow-none" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Price (NIS)</label>
                                    <input type="number" name="price" class="form-control shadow-none" value="<?php echo $product['price']; ?>" step="0.01" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Stock Quantity</label>
                                    <input type="number" name="stock" class="form-control shadow-none" value="<?php echo $product['stock']; ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Category</label>
                                <select name="category_id" class="form-select shadow-none" required>
                                    <?php
                                    $cats = mysqli_query($conn, "SELECT * FROM categories");
                                    while($cat = mysqli_fetch_assoc($cats)) {
                                        $selected = ($cat['id'] == $product['category_id']) ? 'selected' : '';
                                        echo "<option value='".$cat['id']."' $selected>".$cat['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">Description</label>
                                <textarea name="description" class="form-control shadow-none" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="update_product" class="btn btn-primary px-5 shadow-sm">
                                    <i class="bi bi-check-lg me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>