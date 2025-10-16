<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Your Cart - SpareHub</title>
    <link href="../styles/carts.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
</head>

<body>
    <div class="navbar">
        <div class="brand">SpareHub</div>
        <div class="links">
            <ul>
                <li><a href="./Homepage.php">Home</a></li>
                <li><a href="./about.html">About</a></li>
            </ul>
        </div>
    </div>
    <section class="main">
        <div class="cart-container">
            <h1>Your Cart</h1>
            <div id="cart-items"></div>
            <div class="cart-total" id="cart-total"></div>
            <a class="buy-all-btn" href="payment.php" id="buy-all-btn" style="display:none;">Buy</a>
            <div class="empty-cart" id="empty-cart" style="display:none;">Your cart is empty.</div>
            <a href="results.php" style="display:block;text-align:center;margin-top:20px;">← Continue Shopping</a>
        </div>
    </section>
    <script>
        function renderCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsDiv = document.getElementById('cart-items');
            const cartTotalDiv = document.getElementById('cart-total');
            const buyAllBtn = document.getElementById('buy-all-btn');
            const emptyCartDiv = document.getElementById('empty-cart');
            cartItemsDiv.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                cartTotalDiv.textContent = '';
                buyAllBtn.style.display = 'none';
                emptyCartDiv.style.display = 'block';
                return;
            }

            emptyCartDiv.style.display = 'none';
            buyAllBtn.style.display = 'block';

            cart.forEach((item, idx) => {
                let priceNum = parseFloat(item.price.replace(/[^0-9.]/g, '')) || 0;
                total += priceNum * item.quantity;

                const div = document.createElement('div');
                div.className = 'cart-item';
                div.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <div class="cart-item-details">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">${item.price}</div>
                    <div class="cart-item-qty">Quantity: ${item.quantity}</div>
                </div>
                <button class="remove-btn" data-idx="${idx}">Remove</button>
            `;
                cartItemsDiv.appendChild(div);
            });

            cartTotalDiv.textContent = 'Total: ₹' + total.toFixed(2);

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.onclick = function () {
                    const idx = parseInt(this.getAttribute('data-idx'));
                    cart.splice(idx, 1);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                };
            });
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').textContent = count;
        }

        renderCart();
        updateCartCount();
    </script>
</body>

</html>