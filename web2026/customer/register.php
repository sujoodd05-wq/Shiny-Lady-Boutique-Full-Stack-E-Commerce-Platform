<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Shiny Lady</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!--  Icons  -->
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
        .register-card {
            background: white;
            padding: 50px;
            width: 450px;
            box-shadow: 0 10px 30px rgba(117,75,77,0.1);
            border-radius: 15px;
            text-align: center;
        }
        .register-card h2 { color: var(--plum-wine); letter-spacing: 2px; margin-bottom: 10px; }
        .register-card p { color: var(--dusty-rose); font-size: 0.9rem; margin-bottom: 30px; }

        .form-control-custom {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--rosewater);
            border-radius: 5px;
            outline: none;
            margin-bottom: 15px;
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

<div class="register-card">
    <h2>Join Us</h2>
    <p>Create an account to start shining</p>

    <form action="auth-logic.php" method="POST">
        <input type="text" name="full_name" class="form-control-custom" placeholder="Your Full Name" required>

        <input type="email" name="email" class="form-control-custom" placeholder="Email Address" required>

        <input type="text" name="phone" class="form-control-custom" placeholder="Phone Number (e.g. 059...)" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="registerPass" class="form-control-custom" placeholder="Create Password" style="margin-bottom: 0;" required>
            <i class="bi bi-eye-slash toggle-icon" id="eyeIcon"></i>
        </div>

        <button type="submit" name="register" class="btn-add" style="width: 100%; background: var(--plum-wine); color: white; border: none; padding: 12px; cursor: pointer; border-radius: 5px; font-weight: 600;">CREATE ACCOUNT</button>
    </form>

    <p style="margin-top: 25px; font-size: 0.8rem;">Already have an account? <a href="login.php" style="color: var(--copper-rose); font-weight: 600; text-decoration: none;">Login Here</a></p>
</div>

<script>
    const passInput = document.getElementById('registerPass');
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