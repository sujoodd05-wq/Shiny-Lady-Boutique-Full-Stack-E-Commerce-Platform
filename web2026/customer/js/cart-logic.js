function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let count = cart.reduce((total, item) => total + (parseInt(item.qty) || 0), 0);

    let badge = document.getElementById("cartBadge");
    if (badge) {
        badge.innerText = count;
    }
}

function addToCart(name, price, image) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let existingItem = cart.find(item => item.name === name);

    if (existingItem) {
        existingItem.qty = (parseInt(existingItem.qty) || 0) + 1;
    } else {
        cart.push({ name: name, price: price, image: image, qty: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    alert(name + " added to bag! ✨");
}
function changeQty(index, delta) {
    let cart = JSON.parse(localStorage.getItem("cart"));
    cart[index].qty += delta;
    if (cart[index].qty < 1) cart[index].qty = 1;
    localStorage.setItem("cart", JSON.stringify(cart));

    if(typeof renderCart === "function") renderCart();
    updateCartCount();
}

function confirmOrder() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    if (cart.length === 0) {
        alert("Your shopping bag is empty!");
        return;
    }

    if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
        alert("Please login to your account to confirm the order.");
        window.location.href = "login.php";
        return;
    }

    const checkoutArea = document.getElementById('checkout-area');
    if (checkoutArea) checkoutArea.style.display = 'grid';
    checkoutArea.scrollIntoView({ behavior: 'smooth' });
}

function processOrder() {
    const name = document.getElementById("cust-name").value;
    const phone = document.getElementById("cust-phone").value;
    const city = document.getElementById("cust-city").value;
    const address = document.getElementById("cust-address").value;
    const region = document.getElementById("cust-region").value;

    if (!name || !phone || !city || !address) {
        alert("Please fill in all delivery details.");
        return;
    }

    const cart = JSON.parse(localStorage.getItem("cart"));
    let subtotal = cart.reduce((acc, i) => acc + (i.price * i.qty), 0);
    let ship = region === "West Bank" ? 20 : (region === "Jerusalem" ? 40 : 70);
    let total = subtotal + ship;

    let formData = new FormData();
    formData.append('name', name);
    formData.append('phone', phone);
    formData.append('region', region);
    formData.append('city', city);
    formData.append('address', address);
    formData.append('total', total);
    formData.append('items', JSON.stringify(cart));

    fetch('place-order.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "SUCCESS") {
                let msg = `*✨ NEW BOUTIQUE ORDER ✨*%0A*Customer:* ${name}%0A*Total:* ${total} NIS%0A*Items:*%0A`;
                cart.forEach(i => msg += `- ${i.name} (x${i.qty})%0A`);

                window.open(`https://wa.me/970597163105?text=${msg}`, '_blank');

                localStorage.removeItem("cart");
                alert("Order saved! Redirecting to WhatsApp...");
                window.location.href = "index.php";
            } else {
                alert("Server Error: " + data);
            }
        });
}

const aboutObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelector('.animate-left')?.classList.add('appear');
            entry.target.querySelector('.animate-right')?.classList.add('appear');
        }
    });
}, { threshold: 0.3 });

function triggerAbout() {
    setTimeout(() => {
        document.querySelector('.animate-left')?.classList.add('appear');
        document.querySelector('.animate-right')?.classList.add('appear');
    }, 300);
}

document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const cName = document.getElementById('contactName').value;
    const cEmail = document.getElementById('contactEmail').value;
    const cMsg = document.getElementById('contactMessage').value;

    let text = `*✨ Inquiry from Website ✨*%0A*Name:* ${cName}%0A*Email:* ${cEmail}%0A*Message:* ${cMsg}`;
    window.open(`https://wa.me/970597163105?text=${text}`, '_blank');
    this.reset();
});
function toggleMobileNav() {
    const nav = document.querySelector('.nav-links');
    nav.classList.toggle('active');

    const icon = document.querySelector('.mobile-nav-toggle i');
    if (nav.classList.contains('active')) {
        icon.classList.replace('bi-list', 'bi-x-lg');
    } else {
        icon.classList.replace('bi-x-lg', 'bi-list');
    }
}

document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.nav-links').classList.remove('active');
        document.querySelector('.mobile-nav-toggle i').classList.replace('bi-x-lg', 'bi-list');
    });
});
function toggleMobileNav() {
    const nav = document.getElementById('main-nav');
    nav.classList.toggle('open');
}
document.addEventListener("DOMContentLoaded", updateCartCount);

window.addEventListener('load', updateCartCount);
function filterByPrice() {
    const maxPrice = document.getElementById('priceRange').value;
    document.getElementById('priceValue').innerText = maxPrice + " NIS";

    const products = document.querySelectorAll('.product-card');
    let count = 0;

    products.forEach(product => {
        const priceText = product.querySelector('.price').innerText;
        const price = parseFloat(priceText.replace(' NIS', ''));

        if (price <= maxPrice) {
            product.style.display = 'block';
            count++;
        } else {
            product.style.display = 'none';
        }
    });
    document.getElementById('visibleCount').innerText = count;
}
function filterByPrice() {
    const range = document.getElementById('priceRange');
    const valDisplay = document.getElementById('priceValue');
    const countDisplay = document.getElementById('visibleCount');

    if (!range) return;

    const maxPrice = range.value;
    valDisplay.innerText = maxPrice + " NIS";

    const products = document.querySelectorAll('.product-card');
    let count = 0;

    products.forEach(product => {
        const priceText = product.querySelector('.price').innerText;
        const price = parseFloat(priceText.replace(' NIS', ''));

        if (price <= maxPrice) {
            product.style.display = 'block';
            count++;
        } else {
            product.style.display = 'none';
        }
    });
    countDisplay.innerText = count;
}

document.addEventListener('DOMContentLoaded', filterByPrice);