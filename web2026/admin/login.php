<?php
session_start();
require_once '../config/db.php';
global $conn;

$error = "";

if (isset($_POST['login_btn'])) {
    $user_input = trim(mysqli_real_escape_string($conn, $_POST['username_or_email']));
    $password_input = trim($_POST['password']);

    $query = "SELECT * FROM admins WHERE email = '$user_input' OR username = '$user_input' LIMIT 1";
    $res = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($res);

    if ($admin) {
        if (password_verify($password_input, $admin['password'])) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['full_name'];
            $_SESSION['role'] = $admin['role'];

            if (function_exists('logActivity')) {
                logActivity("Login", "Admin successfully logged into the dashboard");
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "Admin account not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access | Shiny Lady Boutique</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        body {
            background-color: var(--china-doll);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            background: white;
            padding: 45px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(117, 75, 77, 0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--rosewater);
        }
        .login-card h3 {
            color: var(--plum-wine);
            font-weight: 600;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: var(--dusty-rose);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid var(--rosewater);
            background-color: #fdfdfd;
        }
        .form-control:focus {
            border-color: var(--plum-wine);
            box-shadow: none;
            background-color: #fff;
        }
        .btn-login {
            background-color: var(--plum-wine);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: var(--copper-rose);
            transform: translateY(-2px);
            color: white;
        }

        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: var(--plum-wine);
            font-size: 1.2rem;
            z-index: 10;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <h3>BOUTIQUE</h3>
    <p class="text-muted small mb-4">Administration Access Only</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 small border-0 mb-4" style="background-color: #fceaea; color: #842029;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">Username or Email</label>
            <input type="text" name="username_or_email" class="form-control shadow-none" placeholder="admin" required>
        </div>

        <div class="mb-4 text-start password-container">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="passwordField" class="form-control shadow-none" placeholder="••••••••" required>
            <i class="bi bi-eye-slash toggle-password" id="toggleIcon"></i>
        </div>

        <button type="submit" name="login_btn" class="btn btn-login w-100 shadow-sm">
            ACCESS DASHBOARD
        </button>
    </form>

    <div class="mt-4">
        <a href="../customer/index.php" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left"></i> Return to Shop
        </a>
    </div>
</div>

<script>
    const passwordField = document.getElementById('passwordField');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleIcon.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>