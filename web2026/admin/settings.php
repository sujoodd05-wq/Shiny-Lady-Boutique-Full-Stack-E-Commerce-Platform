<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied: Managers only!'); window.location.href='index.php';</script>";
    exit();
}

$admin_id = $_SESSION['admin_id'];

if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if(mysqli_query($conn, "UPDATE admins SET full_name='$full_name', username='$username', email='$email' WHERE id=$admin_id")) {
        if (function_exists('logActivity')) {
            logActivity("Settings", "Updated their personal profile");
        }
        header("Location: settings.php?msg=ProfileUpdated");
        exit();
    }
}

if (isset($_POST['update_store'])) {
    $s_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
    $s_email = mysqli_real_escape_string($conn, $_POST['shop_email']);
    $s_phone = mysqli_real_escape_string($conn, $_POST['shop_phone']);
    $s_address = mysqli_real_escape_string($conn, $_POST['shop_address']);

    if(mysqli_query($conn, "UPDATE settings SET shop_name='$s_name', shop_email='$s_email', shop_phone='$s_phone', shop_address='$s_address' WHERE id=1")) {
        if (function_exists('logActivity')) {
            logActivity("Settings", "Updated global store information");
        }
        header("Location: settings.php?msg=StoreUpdated");
        exit();
    }
}

$admin_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE id=$admin_id"));
$store_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings WHERE id=1"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Shiny Lady Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .settings-card { border: none; border-radius: 15px; background: white; box-shadow: 0 4px 20px rgba(117, 75, 77, 0.05); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: var(--plum-wine); margin-bottom: 5px; text-transform: uppercase; }
        .form-control { border-radius: 8px; border: 1px solid var(--rosewater); padding: 12px; }
        .form-control:focus { border-color: var(--plum-wine); box-shadow: none; background-color: #fdfaf9; }
        .section-icon { color: var(--copper-rose); font-size: 1.3rem; margin-right: 10px; }
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
            <a href="orders.php"><i class="bi bi-cart-fill me-3"></i> Orders</a>
            <a href="customers.php"><i class="bi bi-people me-3"></i> Customers</a>
            <a href="analytics.php"><i class="bi bi-graph-up me-3"></i> Analytics</a>

            <a href="settings.php" class="active"><i class="bi bi-gear me-3"></i> Settings</a>

            <div style="margin-top: 50px; padding-bottom: 30px;">
                <a href="login.php" onclick="return confirm('Are you sure you want to logout?')" class="text-white-50">
                    <i class="bi bi-box-arrow-left me-3"></i> Logout
                </a>
            </div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <h2 class="fw-bold text-plum-wine mb-4">Store Configuration</h2>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success border-0 shadow-sm py-2 small mb-4" style="background: var(--rosewater); color: var(--plum-wine);">
                    <i class="bi bi-check-circle-fill me-2"></i> Settings updated successfully!
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card settings-card p-4 mb-4">
                        <h5 class="fw-bold mb-4 text-plum-wine"><i class="bi bi-person-bounding-box section-icon"></i>My Admin Profile</h5>
                        <form action="settings.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Display Name</label>
                                    <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($admin_data['full_name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Login Username</label>
                                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary px-4 shadow-sm">Update Profile</button>
                        </form>
                    </div>

                    <div class="card settings-card p-4">
                        <h5 class="fw-bold mb-4 text-plum-wine"><i class="bi bi-shop section-icon"></i>Store Public Information</h5>
                        <form action="settings.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Store Name</label>
                                    <input type="text" name="shop_name" class="form-control" value="Shiny Lady Boutique" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Official Gmail</label>
                                    <input type="email" name="shop_email" class="form-control" value="ShinyLady.Boutique.Pal@gmail.com" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Support WhatsApp</label>
                                    <input type="text" name="shop_phone" class="form-control" value="+970 597 163 105">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Main Location</label>
                                    <input type="text" name="shop_address" class="form-control" value="Palestine, West Bank">
                                </div>
                            </div>
                            <button type="submit" name="update_store" class="btn btn-primary px-4 shadow-sm">Save Official Settings</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card settings-card p-4 mb-4">
                        <h5 class="fw-bold mb-4 text-plum-wine"><i class="bi bi-shield-lock section-icon"></i>Change Password</h5>
                        <form action="update-password.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small">Enter New Password</label>
                                <input type="password" name="new_pass" class="form-control" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-outline-dark w-100 border-2 fw-bold" style="border-color: var(--plum-wine); color: var(--plum-wine);">Update Security</button>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>