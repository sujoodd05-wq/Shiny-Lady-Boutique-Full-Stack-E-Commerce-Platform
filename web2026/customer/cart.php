<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Checkout | Shiny Lady Boutique</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-wrapper { padding: 60px 10%; background: #fff; min-height: 80vh; }
        .cart-table th { text-align: left; padding: 15px; border-bottom: 2px solid var(--china-doll); color: var(--plum-wine); font-size: 0.8rem; text-transform: uppercase; }
        .cart-table td { padding: 20px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }


        .item-checkbox { width: 18px; height: 18px; accent-color: var(--plum-wine); cursor: pointer; }

        .qty-controls { display: flex; align-items: center; border: 1px solid var(--rosewater); width: fit-content; border-radius: 5px; }
        .qty-btn {
            background: white;
            border: 1px solid var(--rosewater);
            color: var(--plum-wine);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
        }
        .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; margin-top: 30px; }
        .delivery-form { background: #fcfaf9; padding: 30px; border-radius: 15px; border: 1px solid #eee; }
        .summary-card { background: var(--china-doll); padding: 30px; border-radius: 15px; height: fit-content; position: sticky; top: 100px; }

        .btn-confirm-order { background-color: var(--plum-wine); color: white; border: none; padding: 15px; width: 100%; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; border-radius: 8px; transition: 0.3s; }
        .btn-confirm-order:hover { background-color: var(--copper-rose); transform: translateY(-3px); }

        .modal-content { border-radius: 20px; border: none; }
        .modal-header { background-color: var(--plum-wine); color: white; border-radius: 20px 20px 0 0; }
        .invoice-summary { border-top: 1px dashed #ccc; padding-top: 15px; margin-top: 15px; }

        .header-container {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            height: 70px !important;
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 20px !important;
        }


        .header-icons {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
        }

        .user-greeting {
            font-size: 0.85rem !important;
            color: var(--plum-wine);
            font-weight: 600;
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

<div class="cart-wrapper">
    <h3 class="mb-4 text-plum-wine">SHOPPING BAG</h3>

    <table class="table cart-table" id="cart-table">
        <thead>
        <tr>
            <th><i class="bi bi-check2-square"></i></th>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="cart-items">
        </tbody>
    </table>

    <div class="checkout-grid" id="checkout-area" style="display: none;">
        <div class="delivery-form">
            <h5 class="mb-4"><i class="bi bi-truck me-2"></i>Delivery Information</h5>
            <div class="row g-3">
                <div class="col-md-6"><label class="small fw-bold">Full Name</label><input type="text" id="cust-name" class="form-control" placeholder="Required"></div>
                <div class="col-md-6"><label class="small fw-bold">Phone</label><input type="text" id="cust-phone" class="form-control" placeholder="05xxxxxxxx"></div>
                <div class="col-md-6">
                    <label class="small fw-bold">Region</label>
                    <select id="cust-region" class="form-select" onchange="renderCart()">
                        <option value="West Bank">West Bank (+20 NIS)</option>
                        <option value="Jerusalem">Jerusalem (+40 NIS)</option>
                        <option value="Inside">Inside (+70 NIS)</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="small fw-bold">City</label><input type="text" id="cust-city" class="form-control"></div>
                <div class="col-12"><label class="small fw-bold">Street Address</label><input type="text" id="cust-address" class="form-control"></div>
            </div>
        </div>

        <div class="summary-card shadow-sm">
            <h5 class="mb-4">Order Summary</h5>
            <div class="d-flex justify-content-between mb-2"><span>Selected Subtotal</span><span id="subtotal-val">0 NIS</span></div>
            <div class="d-flex justify-content-between mb-3"><span>Shipping Fee</span><span id="shipping-val">0 NIS</span></div>
            <div class="d-flex justify-content-between fw-bold border-top pt-3 fs-5 text-plum-wine">
                <span>Grand Total</span><span id="grand-total-val">0 NIS</span>
            </div>
            <button class="btn-confirm-order mt-4" onclick="showConfirmationModal()">Review & Confirm</button>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">Final Order Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small">Please review your order details before we send it to the boutique.</p>
                <div id="modal-item-list" class="mb-3"></div>
                <div class="invoice-summary">
                    <div class="d-flex justify-content-between small"><span>Subtotal:</span><b id="modal-subtotal"></b></div>
                    <div class="d-flex justify-content-between small"><span>Shipping:</span><b id="modal-shipping"></b></div>
                    <div class="d-flex justify-content-between fs-5 text-plum-wine mt-2"><span>Total:</span><b id="modal-total"></b></div>
                </div>
                <div class="mt-4 border-top pt-3">
                    <p class="small mb-1"><b>Deliver to:</b> <span id="modal-address"></span></p>
                    <p class="small"><b>Phone:</b> <span id="modal-phone"></span></p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Edit Bag</button>
                <button type="button" class="btn-confirm-order" style="width: auto; padding: 10px 30px;" onclick="finalProcessOrder()">Place Order Now</button>
            </div>
        </div>
    </div>
</div>
<footer style="padding: 60px 10%; background: var(--plum-wine); color: white; text-align: center;">

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/cart-logic.js"></script>
<script>
    const isLoggedIn = <?php echo $isLoggedIn; ?>;
    let selectedItems = [];

    function renderCart() {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        const tbody = document.getElementById("cart-items");
        const checkoutArea = document.getElementById("checkout-area");
        tbody.innerHTML = "";
        let subtotal = 0;

        if (cart.length === 0) {
            tbody.innerHTML = "<tr><td colspan='6' class='text-center py-5'>Your bag is empty.</td></tr>";
            checkoutArea.style.display = "none";
            return;
        }

        checkoutArea.style.display = "grid";
        cart.forEach((item, index) => {
            const isChecked = item.selected !== false;
            let total = item.price * item.qty;
            if(isChecked) subtotal += total;

            tbody.innerHTML += `<tr>
                <td><input type="checkbox" class="item-checkbox" ${isChecked ? 'checked' : ''} onchange="toggleItem(${index})"></td>
                <td class="d-flex align-items-center gap-3">
                    <img src="${item.image}" width="50" class="rounded">
                    <span class="fw-bold text-plum-wine">${item.name}</span>
                </td>
                <td>${item.price} NIS</td>
                <td>
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="changeQty(${index}, -1)">-</button>
                        <span class="px-2">${item.qty}</span>
                        <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>
                    </div>
                </td>
                <td class="fw-bold">${total} NIS</td>
                <td><button onclick="removeItem(${index})" class="btn text-danger p-0"><i class="bi bi-trash"></i></button></td>
            </tr>`;
        });

        const region = document.getElementById("cust-region").value;
        let shipping = region === "West Bank" ? 20 : (region === "Jerusalem" ? 40 : 70);
        document.getElementById("subtotal-val").innerText = subtotal + " NIS";
        document.getElementById("shipping-val").innerText = shipping + " NIS";
        document.getElementById("grand-total-val").innerText = (subtotal + shipping) + " NIS";
    }

    function toggleItem(index) {
        let cart = JSON.parse(localStorage.getItem("cart"));
        cart[index].selected = !cart[index].selected;
        localStorage.setItem("cart", JSON.stringify(cart));
        renderCart();
    }

    function showConfirmationModal() {
        if (!isLoggedIn) { alert("Please login first!"); window.location.href="login.php"; return; }

        const name = document.getElementById("cust-name").value;
        const phone = document.getElementById("cust-phone").value;
        if (!name || !phone) { alert("Please fill your info"); return; }

        const cart = JSON.parse(localStorage.getItem("cart"));
        selectedItems = cart.filter(i => i.selected !== false);
        if(selectedItems.length === 0) { alert("Please select at least one item to buy."); return; }

        let listHtml = "";
        let sub = 0;
        selectedItems.forEach(i => {
            listHtml += `<div class="d-flex justify-content-between small mb-1"><span>${i.name} (x${i.qty})</span><span>${i.price * i.qty} NIS</span></div>`;
            sub += (i.price * i.qty);
        });

        const region = document.getElementById("cust-region").value;
        let ship = region === "West Bank" ? 20 : (region === "Jerusalem" ? 40 : 70);

        document.getElementById("modal-item-list").innerHTML = listHtml;
        document.getElementById("modal-subtotal").innerText = sub + " NIS";
        document.getElementById("modal-shipping").innerText = ship + " NIS";
        document.getElementById("modal-total").innerText = (sub + ship) + " NIS";
        document.getElementById("modal-address").innerText = document.getElementById("cust-city").value + ", " + region;
        document.getElementById("modal-phone").innerText = phone;

        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }

    function finalProcessOrder() {
        const region = document.getElementById("cust-region").value;
        const sub = selectedItems.reduce((acc, i) => acc + (i.price * i.qty), 0);
        const ship = region === "West Bank" ? 20 : (region === "Jerusalem" ? 40 : 70);

        let formData = new FormData();
        formData.append('name', document.getElementById("cust-name").value);
        formData.append('phone', document.getElementById("cust-phone").value);
        formData.append('region', region);
        formData.append('city', document.getElementById("cust-city").value);
        formData.append('address', document.getElementById("cust-address").value);
        formData.append('total', (sub + ship));
        formData.append('items', JSON.stringify(selectedItems));

        fetch('place-order.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "SUCCESS") {
                    let cart = JSON.parse(localStorage.getItem("cart"));
                    cart = cart.filter(i => i.selected === false);
                    localStorage.setItem("cart", JSON.stringify(cart));

                    alert("✨ Order Placed Successfully!");
                    window.location.href = "index.php";
                }
            });
    }

    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));
        updateCartCount();
        if (typeof renderCart === "function") renderCart();
    }


    renderCart();
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
</body>
</html>