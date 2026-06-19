<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shiny Lady</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: var(--china-doll);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 50px;
            width: 400px;
            box-shadow: 0 10px 30px rgba(117,75,77,0.1);
            border-radius: 10px;
            text-align: center;
        }
        .login-card h2 { color: var(--plum-wine); letter-spacing: 2px; margin-bottom: 10px; }
        .login-card p { color: var(--dusty-rose); font-size: 0.9rem; margin-bottom: 30px; }

        .form-control-custom {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--rosewater);
            border-radius: 5px;
            outline: none;
        }

        .password-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        .toggle-icon {
            position: absolute;
            right: 15px;
            top: 12px;
            cursor: pointer;
            color: var(--plum-wine);
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Welcome Back</h2>
    <p>Login to manage your orders</p>

    <?php if(isset($_GET['error'])): ?>
        <p style="color: red; font-size: 0.8rem; margin-bottom: 15px;">Invalid email or password.</p>
    <?php endif; ?>

    <form action="auth-logic.php" method="POST">
        <input type="email" name="email" class="form-control-custom" placeholder="Email Address" style="margin-bottom: 15px;" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="customerPass" class="form-control-custom" placeholder="Password" required>
            <i class="bi bi-eye-slash toggle-icon" id="eyeIcon"></i>
        </div>

        <button type="submit" name="login" class="btn-add" style="width: 100%; cursor: pointer;">LOGIN</button>
    </form>

    <p style="margin-top: 25px; font-size: 0.8rem;">Don't have an account? <a href="register.php" style="color: var(--copper-rose); font-weight: 600; text-decoration: none;">Register Here</a></p>
    <a href="index.php" style="display: block; margin-top: 15px; font-size: 0.8rem; color: var(--plum-wine); text-decoration: none;">← Back to Shop</a>
</div>

<script>
    const passInput = document.getElementById('customerPass');
    const eyeBtn = document.getElementById('eyeIcon');

    eyeBtn.addEventListener('click', function() {

        const isPassword = passInput.getAttribute('type') === 'password';
        passInput.setAttribute('type', isPassword ? 'text' : 'password');

        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>