<?php
session_start();
require_once '../config/db.php';
global $conn;

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied: Admins only!'); window.location.href='index.php';</script>";
    exit();
}

if (isset($_POST['add_staff'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (full_name, username, email, password, role) 
            VALUES ('$name', '$user', '$email', '$pass', '$role')";

    if (mysqli_query($conn, $sql)) {
        if (function_exists('logActivity')) {
            logActivity("Management", "Added new $role: $user");
        }
        header("Location: manage-admins.php?msg=UserAdded");
        exit();
    }
}

if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    if ($del_id != 1 && $del_id != $_SESSION['admin_id']) {
        mysqli_query($conn, "DELETE FROM admins WHERE id = $del_id");
        header("Location: manage-admins.php?msg=UserDeleted");
        exit();
    }
}

$admins = mysqli_query($conn, "SELECT * FROM admins ORDER BY role ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff | Shiny Lady Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>

<div class="container-fluid">
    <div class="row flex-column flex-md-row">

        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="text-center mb-5"><h4 class="fw-bold mt-4 text-white">SHINY LADY</h4><hr style="color: var(--rosewater); opacity: 0.5;"></div>
            <a href="index.php"><i class="bi bi-house-door me-3"></i> Dashboard</a>
            <a href="inventory.php"><i class="bi bi-box-seam me-3"></i> Inventory</a>
            <a href="categories.php"><i class="bi bi-tags me-3"></i> Categories</a>
            <a href="orders.php"><i class="bi bi-cart me-3"></i> Orders</a>
            <a href="customers.php"><i class="bi bi-people me-3"></i> Customers</a>
            <a href="settings.php"><i class="bi bi-gear me-3"></i> Settings</a>
            <div style="margin-top: 50px;"><a href="login.php" class="text-white-50"><i class="bi bi-box-arrow-left me-3"></i> Logout</a></div>
        </nav>

        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <h2 class="fw-bold text-plum-wine mb-4">Staff Management</h2>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success py-2 small border-0 shadow-sm mb-4" style="background: var(--rosewater); color: var(--plum-wine);">
                    <i class="bi bi-check-circle me-2"></i> Operation completed successfully.
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                        <h5 class="fw-bold text-plum-wine mb-3">Add New Member</h5>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="full_name" class="form-control shadow-none" placeholder="e.g. Jana Ahmed" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control shadow-none" placeholder="jana_staff" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control shadow-none" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password</label>
                                <input type="password" name="password" class="form-control shadow-none" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Role</label>
                                <select name="role" class="form-select shadow-none">
                                    <option value="staff">Staff (Limited)</option>
                                    <option value="admin">Admin (Full Control)</option>
                                </select>
                            </div>
                            <button type="submit" name="add_staff" class="btn btn-primary w-100 shadow-sm">Create Account</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                <tr class="small text-muted">
                                    <th class="ps-4">Team Member</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($row = mysqli_fetch_assoc($admins)): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-plum-wine"><?php echo $row['full_name']; ?></div>
                                            <small class="text-muted">@<?php echo $row['username']; ?></small>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo $row['role'] == 'admin' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info'; ?> px-3">
                                                <?php echo strtoupper($row['role']); ?>
                                            </span>
                                        </td>
                                        <td class="small"><?php echo $row['email']; ?></td>
                                        <td class="text-end pe-4">
                                            <?php if($row['id'] != 1 && $row['id'] != $_SESSION['admin_id']): ?>
                                                <a href="manage-admins.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Remove this member?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">Protected</span>
                                            <?php endif; ?>
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
</body>
</html>