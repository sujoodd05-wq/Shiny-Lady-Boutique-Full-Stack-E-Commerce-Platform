<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $cat_id = $_POST['category_id'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image_name = $_FILES['product_image']['name'];
    $temp_name = $_FILES['product_image']['tmp_name'];
    $folder = "../images/" . $image_name;

    if (move_uploaded_file($temp_name, $folder)) {
        $sql = "INSERT INTO products (name, price, stock, category_id, image, description) 
                VALUES ('$name', '$price', '$stock', '$cat_id', '$image_name', '$description')";

        if (mysqli_query($conn, $sql)) {

            $admin_name = $_SESSION['user_name'];
            $log_details = "Added: $name | Price: $price NIS | Stock: $stock pieces";

            mysqli_query($conn, "INSERT INTO activity_log (admin_id, admin_name, action, details) 
                                 VALUES ('{$_SESSION['admin_id']}', '$admin_name', 'Inventory', '$log_details')");

            echo "<script>alert('Product Added Successfully! ✨'); window.location.href='inventory.php';</script>";
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Error: Please check if the images folder exists and has write permissions!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Shiny Lady Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .form-control, .form-select {
            background-color: #fdfdfd;
            border: 1px solid var(--rosewater);
            border-radius: 10px;
            padding: 12px;
            color: var(--plum-wine);
        }
        .form-control:focus { border-color: var(--plum-wine); box-shadow: none; }
        .upload-box {
            border: 2px dashed var(--rosewater);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            background: #fff;
            transition: 0.3s;
        }
        .upload-box:hover { background-color: #fcfaf9; border-color: var(--plum-wine); }
        .fw-bold { color: var(--plum-wine); }
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
            <a href="analytics.php"><i class="bi bi-graph-up me-3"></i> Analytics</a>
            <a href="settings.php"><i class="bi bi-gear me-3"></i> Settings</a>

            <div style="margin-top: 50px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Are you sure you want to logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <form action="add-product.php" method="POST" enctype="multipart/form-data">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">New Accessory</h2>
                    <div class="d-flex gap-2">
                        <a href="inventory.php" class="btn btn-outline-secondary px-4 border-0">Cancel</a>
                        <button type="submit" name="save_product" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Publish Product
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="mb-4">
                                <label class="form-label fw-bold small">Product Title</label>
                                <input type="text" name="name" class="form-control shadow-none" placeholder="e.g. Copper Rose Necklace" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold small">Product Description</label>
                                <textarea name="description" class="form-control shadow-none" rows="6" placeholder="Describe materials, size, etc..."></textarea>
                            </div>
                        </div>
                        <div class="card p-4 border-0 shadow-sm" style="border-radius: 15px;">
                            <label class="form-label fw-bold small">Price (NIS)</label>
                            <input type="number" name="price" class="form-control shadow-none" placeholder="0.00" step="0.01" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-4 border-0 shadow-sm mb-4 text-center" style="border-radius: 15px;">
                            <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                                <i class="bi bi-image text-rosewater fs-1"></i>
                                <p class="text-muted small mt-2">Click here to upload <br> product photo</p>
                                <input type="file" name="product_image" id="fileInput" hidden required>
                            </div>
                        </div>

                        <div class="card p-4 border-0 shadow-sm" style="border-radius: 15px;">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Category</label>
                                <select name="category_id" class="form-select shadow-none" required>
                                    <option selected disabled>Select category</option>
                                    <?php
                                    $cats = mysqli_query($conn, "SELECT * FROM categories");
                                    while($c = mysqli_fetch_assoc($cats)) {
                                        echo "<option value='".$c['id']."'>".$c['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Stock Quantity</label>
                                <input type="number" name="stock" class="form-control shadow-none" placeholder="e.g. 15" required>
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-info-circle me-1"></i> This item will be visible in the selected category immediately.
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
    document.getElementById('fileInput').onchange = function() {
        alert("Image selected: " + this.files[0].name);
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>