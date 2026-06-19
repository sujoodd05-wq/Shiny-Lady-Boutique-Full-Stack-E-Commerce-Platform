<?php
session_start();
require_once '../config/db.php';
global $conn;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Necklaces | Boutique Luxury</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .shop-header-premium {
            position: relative;
            width: 100%;
            height: 400px;
            background-image: url('../images/nec.PNG');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 50px;
        }

        .header-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(117, 75, 77, 0.4);
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .header-content h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            letter-spacing: 5px;
            text-transform: uppercase;
            color: white !important;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .header-content p {
            font-size: 1.1rem;
            letter-spacing: 2px;
            opacity: 0.9;
            color: #fdfaf9 !important;
        }

        .luxury-divider {
            display: flex; align-items: center; justify-content: center; gap: 15px; margin-top: 20px;
        }
        .luxury-divider span { width: 50px; height: 1px; background-color: white; opacity: 0.6; }
        .luxury-divider i { color: var(--rosewater); font-size: 0.8rem; }

        .product-container { padding: 40px 10%; background-color: #fcfcfc; }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        @media (max-width: 992px) { .product-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .shop-header-premium { height: 250px; background-attachment: scroll; }
            .product-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
        }

        .product-card {
            background: white; border: 1px solid #eee; padding: 15px; text-align: center;
            transition: 0.4s; display: flex; flex-direction: column; justify-content: space-between;
        }
        .product-card:hover { box-shadow: 0 10px 30px rgba(117, 75, 77, 0.1); transform: translateY(-5px); }
        .product-card img { width: 100%; height: 350px; object-fit: cover; margin-bottom: 20px; }

        .price { font-weight: 600; color: var(--copper-rose); margin-bottom: 10px; }
        .btn-add-item {
            background-color: var(--plum-wine); color: white; border: none; padding: 12px;
            width: 100%; cursor: pointer; text-transform: uppercase; letter-spacing: 2px;
            font-size: 0.75rem; transition: 0.3s;
        }
        .btn-add-item:hover { background-color: var(--copper-rose); }
        .btn-add-item:disabled { background-color: #ccc; cursor: not-allowed; }

        .user-greeting { font-size: 0.8rem; color: var(--plum-wine); font-weight: 600; margin-right: 10px; }
        .product-description-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(117, 75, 77, 0.85);
            color: white;
            display: flex; align-items: center; justify-content: center;
            padding: 20px; text-align: center;
            opacity: 0; /* مخفي */
            transition: 0.5s ease;
        }

        .product-image-wrapper:hover .product-description-overlay {
            opacity: 1;
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 350px;
        }
        .filter-wrapper-section {
            width: 100%;
            margin-bottom: 40px;
            text-align: left;
        }

        .filter-pill-box {
            background-color: var(--china-doll);
            display: inline-block;
            padding: 15px 40px;
            border-radius: 50px;
            border: 1px solid var(--rosewater);
            margin-left: 10%;
        }

        .luxury-slider {
            width: 200px !important;
            accent-color: var(--plum-wine) !important;
            vertical-align: middle;
        }

        .text-copper {
            color: var(--copper-rose);
            font-weight: 600;
        }

        .visible-items-tag {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--plum-wine);
            border-left: 1px solid var(--rosewater);
            padding-left: 15px;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .filter-wrapper-section { text-align: center; }
            .filter-pill-box { margin-left: 0; padding: 15px 25px; width: 95%; }
            .luxury-slider { width: 150px !important; }
        }
    </style>
</head>
<body>

<header class="fixed-top shadow-sm" style="background: white;">
    <div class="header-container">
        <a href="index.php" class="logo">SHINY LADY</a>
        <nav id="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="active">Shop <i class="bi bi-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="shop.php">Shop All</a></li>
                        <li><a href="necklaces.php">Necklaces</a></li>
                        <li><a href="rings.php">Rings</a></li>
                        <li><a href="earrings.php">Earrings</a></li>
                        <li><a href="bracelets.php">Bracelets</a></li>
                    </ul>
                </li>
                <li><a href="index.php#about">About</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <?php if(isset($_SESSION['user_name'])): ?>
                <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" title="Logout" class="me-3"><i class="bi bi-box-arrow-right"></i></a>
            <?php else: ?>
                <a href="login.php" class="me-3" title="Login"><i class="bi bi-person"></i></a>
            <?php endif; ?>
            <a href="cart.php" class="position-relative">
                <i class="bi bi-bag"></i>
                <span class="cart-count" id="cartBadge"></span>
            </a>
        </div>
    </div>
</header>

<div class="shop-header-premium">
    <div class="header-overlay"></div>
    <div class="header-content">
        <h1>Necklaces</h1>
        <p>Elegance wrapped around your neck</p>
        <div class="luxury-divider">
            <span></span> <i class="bi bi-gem"></i> <span></span>
        </div>
    </div>
</div>

<section class="filter-wrapper-section">
    <div class="container">
        <div class="filter-pill-box shadow-sm">
            <div class="d-flex align-items-center gap-3 flex-wrap justify-content-center">

                <label class="form-label small fw-bold text-plum-wine mb-0">
                    MAX BUDGET: <span id="priceValue" class="text-copper">1000 NIS</span>
                </label>

                <input type="range" class="form-range luxury-slider" id="priceRange"
                       min="0" max="1000" step="10" value="1000" oninput="filterByPrice()">

                <div class="visible-items-tag">
                    Showing <b id="visibleCount">0</b> Pieces
                </div>

            </div>
        </div>
    </div>
</section>

<div class="product-grid">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM products WHERE category_id = 1 ORDER BY id DESC");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stock = $row['stock'];
            ?>
            <div class="product-card">

                <div class="product-image-wrapper">
                    <img src="../images/<?php echo $row['image']; ?>" class="main-img" onerror="this.src='https://via.placeholder.com/350x450'">

                    <div class="product-description-overlay">
                        <div class="overlay-content">
                            <i class="bi bi-info-circle mb-2"></i>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="info">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="price"><?php echo $row['price']; ?> NIS</p>

                    <div class="stock-label mb-2">
                        <?php if ($stock > 0): ?>
                            <small class="text-success"><i class="bi bi-check2"></i> In Stock</small>
                        <?php else: ?>
                            <small class="text-danger">Out of Stock</small>
                        <?php endif; ?>
                    </div>

                    <button class="btn-add-item"
                            onclick="addToCart('<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>, '../images/<?php echo $row['image']; ?>')"
                            <?php echo ($stock <= 0) ? 'disabled' : ''; ?>>
                        <?php echo ($stock > 0) ? 'Add To Cart' : 'Sold Out'; ?>
                    </button>
                </div>

            </div>
            <?php
        }
    } else {
        echo "<div style='grid-column: span 3; text-align: center; padding: 50px;'>New collection coming soon! ✨</div>";
    }
    ?>
</div>


<footer style="padding: 60px; background: var(--plum-wine); color: white; text-align: center;">
    <h5 style="letter-spacing: 3px; font-weight: 400; margin-bottom: 20px;">SHINY LADY BOUTIQUE</h5>
    <div class="social-links-luxury">
        <a href="https://www.instagram.com/shiny_lady11" target="_blank" style="color:white; margin:0 10px;"><i class="bi bi-instagram"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61552096407180" target="_blank" style="color:white; margin:0 10px;"><i class="bi bi-facebook"></i></a>
        <a href="https://wa.me/970597163105" target="_blank" style="color:white; margin:0 10px;"><i class="bi bi-whatsapp"></i></a>
    </div>
    <p style="margin-top: 30px; font-size: 0.7rem; opacity: 0.6;">
        &copy; 2025 ALL RIGHTS RESERVED. Palestine.
    </p>
</footer>

<script src="js/cart-logic.js"></script>
<script>
    function refreshCartNumber() {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        let count = cart.reduce((total, item) => total + (parseInt(item.qty) || 0), 0);
        document.getElementById("cartBadge").innerText = count > 0 ? count : "0";
    }
    refreshCartNumber();
</script>
</body>
</html>