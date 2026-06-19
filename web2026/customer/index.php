<?php
session_start();
require_once '../config/db.php';
global $conn;

$allOrders = mysqli_query($conn, "SELECT order_items FROM `orders` ");
$popularity = [];

while($order = mysqli_fetch_assoc($allOrders)) {
    $items = json_decode($order['order_items'], true);
    if($items) {
        foreach($items as $item) {
            $name = $item['name'];
            $qty = (int)$item['qty'];
            // إذا المنتج موجود قبل هيك نزيد عليه الكمية، إذا لأ بنبلش من الصفر
            if(isset($popularity[$name])) {
                $popularity[$name] += $qty;
            } else {
                $popularity[$name] = $qty;
            }
        }
    }
}

arsort($popularity);

$topThreeNames = array_keys(array_slice($popularity, 0, 3));

if(!empty($topThreeNames)) {
    $namesList = "'" . implode("','", array_map(function($n) use ($conn) { return mysqli_real_escape_string($conn, $n); }, $topThreeNames)) . "'";
    $bestSellers = mysqli_query($conn, "SELECT * FROM products WHERE name IN ($namesList) ORDER BY FIELD(name, $namesList)");
} else {
    $bestSellers = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 3");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Shiny Lady Boutique | Luxury Accessories</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>

        .luxury-border-section {
            position: relative;
            min-height: 75vh;
            background-color: var(--china-doll);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .luxury-border-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                    radial-gradient(ellipse at 20% 50%, rgba(117,75,77,0.08) 0%, transparent 60%),
                    radial-gradient(ellipse at 80% 20%, rgba(168,106,101,0.06) 0%, transparent 50%);
            z-index: 0;
        }


        .hero-brand-border {
            position: absolute;
            top: 0;
            height: 100%;
            width: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0;
            overflow: hidden;
            z-index: 1;
        }
        .hero-brand-border.left { left: 0; border-right: 1px solid rgba(117,75,77,0.15); }
        .hero-brand-border.right { right: 0; border-left: 1px solid rgba(117,75,77,0.15); }

        .hero-brand-border img {
            width: 65px;
            opacity: 0.25;
            display: block;
            margin: 8px auto;
            filter: sepia(30%);
        }

        .hero-line {
            position: absolute;
            left: 110px;
            right: 110px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(117,75,77,0.25), transparent);
            z-index: 1;
        }
        .hero-line.top { top: 40px; }
        .hero-line.bottom { bottom: 40px; }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 30px 20px;
            max-width: 700px;
            background: none;
            border-radius: 0;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 0.7rem;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--copper-rose);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 28px;
        }
        .hero-eyebrow::before,
        .hero-eyebrow::after {
            content: "";
            width: 40px;
            height: 1px;
            background: var(--copper-rose);
            opacity: 0.6;
        }
        section{
            margin: 0;
        }
        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.8rem, 5vw, 5rem);
            font-weight: 300;
            line-height: 1.05;
            color: var(--plum-wine);
            margin: 0 0 8px;
            letter-spacing: -1px;
        }
        .hero-title em {
            font-style: italic;
            font-weight: 400;
            color: var(--copper-rose);
        }

        .hero-title-sub {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.8rem, 3.5vw, 3rem);
            font-weight: 300;
            font-style: italic;
            color: var(--plum-wine);
            opacity: 0.6;
            margin: 0 0 30px;
            letter-spacing: 2px;
        }
        .hero-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin: 0 auto 28px;
        }
        .hero-divider span {
            width: 60px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--copper-rose));
        }
        .hero-divider span:last-child {
            background: linear-gradient(90deg, var(--copper-rose), transparent);
        }
        .hero-divider i {
            color: var(--copper-rose);
            font-size: 0.8rem;
            opacity: 0.7;
        }


        .hero-desc {
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 300;
            letter-spacing: 2px;
            color: var(--plum-wine);
            opacity: 0.65;
            margin-bottom: 45px;
            text-transform: uppercase;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: var(--plum-wine);
            color: white;
            text-decoration: none;
            padding: 16px 48px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            border: 1px solid var(--plum-wine);
            transition: all 0.4s ease;
            font-family: 'Poppins', sans-serif;
        }
        .btn-hero-primary:hover {
            background: transparent;
            color: var(--plum-wine);
        }

        .btn-hero-ghost {
            background: transparent;
            color: var(--plum-wine);
            text-decoration: none;
            padding: 16px 48px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            border: 1px solid rgba(117,75,77,0.35);
            transition: all 0.4s ease;
            font-family: 'Poppins', sans-serif;
        }
        .btn-hero-ghost:hover {
            border-color: var(--plum-wine);
            color: var(--plum-wine);
        }

        .hero-stats {
            position: absolute;
            bottom: 55px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 60px;
            z-index: 2;
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--plum-wine);
            line-height: 1;
        }
        .hero-stat-label {
            font-size: 0.6rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--copper-rose);
            opacity: 0.8;
            margin-top: 4px;
            font-family: 'Poppins', sans-serif;
        }

        .hero-scroll {
            position: absolute;
            bottom: 55px;
            right: 130px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            opacity: 0.5;
            transition: opacity 0.3s;
        }
        .hero-scroll:hover { opacity: 1; }
        .hero-scroll span {
            font-size: 0.55rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--plum-wine);
            font-family: 'Poppins', sans-serif;
            writing-mode: vertical-rl;
        }
        .hero-scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, var(--plum-wine), transparent);
            animation: scrollPulse 2s infinite;
        }
        @keyframes scrollPulse {
            0%, 100% { opacity: 0.3; transform: scaleY(1); }
            50% { opacity: 1; transform: scaleY(1.1); }
        }


        @media (max-width: 768px) {
            .hero-brand-border { width: 50px; }
            .hero-brand-border img { width: 35px; }
            .hero-line { left: 50px; right: 50px; }
            .hero-stats { gap: 30px; bottom: 35px; }
            .hero-scroll { display: none; }
        }

        footer p {
            width: 100%;
            text-align: center !important;
            display: block;
        }
        #compliment-text{
            min-height: 40px;
            display: block;
        }
        .user-greeting { font-size: 0.8rem; color: var(--plum-wine); font-weight: 600; margin-right: 15px; }
        .section-title { font-size: 2.5rem; color: var(--plum-wine); letter-spacing: 2px; margin-bottom: 40px; }
        .best-sellers-section { padding: 60px 8%; background-color: #fff; }
        .product-card {
            background: white; border: 1px solid #eee; padding: 20px; text-align: center;
            transition: 0.4s; position: relative; overflow: hidden;
        }
        .product-card:hover { box-shadow: 0 10px 30px rgba(117,75,77,0.1); transform: translateY(-5px); }
        .badge-best { position: absolute; top: 15px; left: 15px; background: var(--copper-rose); color: white; padding: 3px 12px; font-size: 0.7rem; border-radius: 20px; z-index: 5; }
        .product-card img { width: 100%; height: 350px; object-fit: cover; margin-bottom: 20px; }
        html { scroll-behavior: smooth; }

        .beauty-compliment-section {
            padding: 0;
            background: var(--plum-wine);
            width: 100%;
        }
        .compliment-marquee-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: 16px 40px;
        }
        .compliment-diamond {
            color: var(--rosewater);
            font-size: 0.55rem;
            flex-shrink: 0;
        }
        #compliment-text {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 1.15rem;
            color: rgba(255,255,255,0.88);
            letter-spacing: 1.5px;
            margin: 0;
            text-align: center;
        }
        .video-gallery-section {
            padding: 80px 10%;
            background-color: #fff;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .video-item-wrapper {
            position: relative;
            transition: 0.5s ease;
        }

        .video-oval {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1.5;
            border-radius: 150px;
            overflow: hidden;
            border: 1px solid var(--rosewater);
            background-color: var(--china-doll);
        }

        .video-oval video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            transition: 0.5s ease;
        }

        .video-play-icon {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2rem;
            opacity: 0.6;
            pointer-events: none;
            transition: 0.3s;
        }

        .tilt-left { transform: rotate(-3deg); }
        .tilt-right { transform: rotate(3deg); }

        .video-item-wrapper:hover {
            transform: rotate(0deg) scale(1.05);
            z-index: 10;
        }

        .video-item-wrapper:hover video {
            opacity: 1;
        }

        .video-item-wrapper:hover .video-play-icon {
            opacity: 0;
        }

        @media (max-width: 768px) {
            .video-grid { grid-template-columns: repeat(2, 1fr); }
            .video-oval { border-radius: 100px; }
        }
    </style>
</head>
<body>

<header class="fixed-top">
    <div class="header-container">

        <button class="mobile-nav-toggle" onclick="toggleMobileNav()"
                style="display:none; background:none; border:none; font-size:1.4rem; color:var(--plum-wine); cursor:pointer;">
            <i class="bi bi-list"></i>
        </button>

        <a href="index.php" class="logo">SHINY LADY</a>

        <!-- ناف ديسكتوب -->
        <nav id="main-nav">
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li class="dropdown">
                    <a href="#">Shop <i class="bi bi-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="shop.php">Shop All</a></li>
                        <li><a href="necklaces.php">Necklaces</a></li>
                        <li><a href="rings.php">Rings</a></li>
                        <li><a href="earrings.php">Earrings</a></li>
                        <li><a href="bracelets.php">Bracelets</a></li>
                    </ul>
                </li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <?php if(isset($_SESSION['user_name'])): ?>
                <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="login.php" title="Logout" class="me-3"><i class="bi bi-box-arrow-right"></i></a>
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

<section class="luxury-border-section">
    <div class="hero-brand-border left">
        <?php for($i=0; $i<12; $i++): ?>
            <img src="../images/i.PNG" alt="">
        <?php endfor; ?>
    </div>
    <div class="hero-brand-border right">
        <?php for($i=0; $i<12; $i++): ?>
            <img src="../images/i.PNG" alt="">
        <?php endfor; ?>
    </div>
    <div class="hero-line top"></div>
    <div class="hero-line bottom"></div>
    <div class="hero-content">
        <div class="hero-eyebrow">Handcrafted Luxury</div>
        <h1 class="hero-title">Shiny <em>Lady</em></h1>
        <p class="hero-title-sub">Boutique</p>
        <div class="hero-divider"><span></span><i class="bi bi-diamond"></i><span></span></div>
        <p class="hero-desc">Finely crafted accessories for the modern woman</p>
        <div class="hero-actions">
            <a href="shop.php" class="btn-hero-primary">Shop Now</a>
            <a href="#about" class="btn-hero-ghost">Our Story</a>
        </div>
    </div>

    <a href="#bestsellers" class="hero-scroll"><span>Scroll</span><div class="hero-scroll-line"></div></a>
</section>

<section class="beauty-compliment-section">
    <div class="compliment-marquee-wrap">
        <span class="compliment-diamond">◆</span>
        <p id="compliment-text"></p>
        <span class="compliment-diamond">◆</span>
    </div>
</section>

<section class="best-sellers-section" id="bestsellers">
    <div class="container text-center">
        <span class="subtitle" style="color: var(--copper-rose); letter-spacing: 4px;">TRENDING NOW</span>
        <h2 class="section-title">Our Best Sellers</h2>
        <div class="row g-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php while($row = mysqli_fetch_assoc($bestSellers)): ?>
                <div class="product-card">
                    <span class="badge-best">TOP PIECE</span>
                    <img src="../images/<?php echo $row['image']; ?>" alt="Best Seller" onerror="this.src='https://via.placeholder.com/350x450'">
                    <h3 style="font-size: 1.1rem; color: var(--plum-wine);"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="price" style="font-weight: 600; color: var(--copper-rose);"><?php echo $row['price']; ?> NIS</p>
                    <button class="btn-add" onclick="addToCart('<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>, '../images/<?php echo $row['image']; ?>')">Add to Cart</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="video-gallery-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="subtitle" style="color: var(--copper-rose); letter-spacing: 4px;">A Glimpse Into Your Beautiful Orders</span>
            <h2 class="section-title">PACKED WITH LOVE</h2>
        </div>

        <div class="video-grid">

            <div class="video-item-wrapper tilt-left">
                <div class="video-oval">
                    <video muted loop class="hover-video">
                        <source src="../images/video1.mp4" type="video/mp4">
                    </video>
                    <div class="video-play-icon"><i class="bi bi-play-fill"></i></div>
                </div>
            </div>

            <div class="video-item-wrapper tilt-right">
                <div class="video-oval">
                    <video muted loop class="hover-video">
                        <source src="../images/video2.mp4" type="video/mp4">
                    </video>
                    <div class="video-play-icon"><i class="bi bi-play-fill"></i></div>
                </div>
            </div>

            <div class="video-item-wrapper tilt-left">
                <div class="video-oval">
                    <video muted loop class="hover-video">
                        <source src="../images/video3.mp4" type="video/mp4">
                    </video>
                    <div class="video-play-icon"><i class="bi bi-play-fill"></i></div>
                </div>
            </div>

            <div class="video-item-wrapper tilt-right">
                <div class="video-oval">
                    <video muted loop class="hover-video">
                        <source src="../images/video4.mp4" type="video/mp4">
                    </video>
                    <div class="video-play-icon"><i class="bi bi-play-fill"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="about-section">
    <div class="container">
        <div class="about-flex" style="direction: rtl; display: flex; align-items: center; gap: 50px;">
            <div class="about-images animate-left" style="flex: 1; position: relative; min-height: 450px;">
                <div class="image-wrapper main-img" style="position: absolute; z-index: 2;"><img src="../images/img_1.png" style="width: 280px; height: 380px; object-fit: cover; border: 5px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1);"></div>
                <div class="image-wrapper sub-img" style="position: absolute; bottom: 0; right: 0; z-index: 3;"><img src="../images/img.png" style="width: 250px; height: 350px; object-fit: cover; border: 5px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1);"></div>
            </div>
            <div class="about-text animate-right" style="flex: 1; text-align: right;">
                <h2 class="plum-title arabic-font">Shiny Lady Boutique</h2>
                <div class="decorative-line" style="margin-right: 0; margin-left: auto;"></div>
                <div class="arabic-content arabic-font" style="font-size: 1.2rem; line-height: 1.8;">
                    <p>في <strong>Shiny Lady</strong>، إحنا مش بس متجر إكسسوارات… إحنا المكان اللي اللمعة فيه بتعكس شخصيتك </p>
                    <p>نؤمن إن كل قطعة بتلبسيها بتحكي قصة، وكل تفصيلة صغيرة ممكن تغيّر مود يومك بالكامل.</p>
                    <p>نحنا <strong>جنى وسجود</strong>، بدأنا Shiny Lady بحبنا للتفاصيل الصغيرة اللي بتفرق.</p>
                    <h4 style="color: var(--copper-rose);">لأنك تستحقي تلمعي بطريقتك </h4>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact-section">
    <div class="container">
        <div class="contact-header text-center">
            <span class="subtitle">GET IN TOUCH</span>
            <h2 class="plum-title">Contact Us</h2>
            <div class="decorative-line mx-auto"></div>
            <p class="section-desc">ShinyLady.Boutique.Pal@gmail.com</p>
        </div>
        <div class="contact-form-minimal">
            <form id="contactForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <input type="text" id="contactName" placeholder="Full Name" required>
                    <input type="email" id="contactEmail" placeholder="Email Address" required>
                </div>
                <textarea id="contactMessage" rows="4" placeholder="How can we help you?" required></textarea>
                <div class="text-center mt-4">
                    <button type="submit" class="btn-luxury-send">SEND MESSAGE <i class="bi bi-send ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>
</section>

<footer style="padding: 60px; background: var(--plum-wine); color: white; text-align: center;">
    <h5 style="letter-spacing: 3px; font-weight: 400; margin-bottom: 20px;">SHINY LADY BOUTIQUE</h5>
    <div class="social-links-luxury">
        <a href="https://www.instagram.com/shiny_lady11" target="_blank" style="color:white; margin:0 10px;"><i class="bi bi-instagram"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61552096407180" target="_blank" style="color:white; margin:0 10px;"><i class="bi bi-facebook"></i></a>
        <a href="https://wa.me/970597163105" target="_blank" style="color:white; margin:0 10px;"><i class="bi bi-whatsapp"></i></a>
    </div>
    <p style="margin-top: 30px; font-size: 0.7rem; opacity: 0.6; letter-spacing: 1px;">
        &copy; 2025 ALL RIGHTS RESERVED. Palestine.
    </p>
</footer>
<script src="js/cart-logic.js"></script>
<script>
    const compliments = [
        "أنتِ اللمعة اللي بتكمل جمال القطعة ",
        "جمالك اليوم بخطف الأنظار.. تماماً مثل الذهب",
        "Shiny Lady بليق بيكي وبس ",
        "ضحكتك هي أحلى إكسسوار ممكن تلبسيه اليوم",
        "لأنك مميزة، كل قطعة بتستنى لمستك "
    ];

    document.addEventListener('DOMContentLoaded', function () {
        const textElement = document.getElementById("compliment-text");
        if (textElement) {
            const randomMsg = compliments[Math.floor(Math.random() * compliments.length)];
            textElement.innerHTML = randomMsg;
        }
    });

    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const left = document.querySelector('.animate-left');
                const right = document.querySelector('.animate-right');
                if(left) { left.style.opacity = "1"; left.style.transform = "translateX(0)"; }
                if(right) { right.style.opacity = "1"; right.style.transform = "translateX(0)"; }
            }
        });
    }, { threshold: 0.2 });

    const target = document.getElementById('about');
    if(target) obs.observe(target);

    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('contactName').value;
        const msg = document.getElementById('contactMessage').value;
        const finalMsg = `*✨ New Inquiry ✨*%0A*Name:* ${name}%0A*Message:* ${msg}`;
        window.open(`https://wa.me/970597163105?text=${finalMsg}`, '_blank');
    });
</script>
<script src="js/cart-logic.js"></script>
<script>
    function refreshCartNumber() {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        let count = cart.reduce((total, item) => total + (parseInt(item.qty) || 0), 0);
        document.getElementById("cartBadge").innerText = count > 0 ? count : "0";
    }
    refreshCartNumber();
</script>
<script>
    const allVideos = document.querySelectorAll('.hover-video');

    allVideos.forEach(video => {
        video.parentElement.addEventListener('mouseenter', () => {
            video.play();
        });


        video.parentElement.addEventListener('mouseleave', () => {
            video.pause();
            video.currentTime = 0;
        });
    });

</script>
</body>
</html>