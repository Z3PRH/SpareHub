<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit();
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpareHub - Working Filters</title>
    <link rel="stylesheet" href="../styles/result.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="results-container">
        <div class="navbar">
            <div class="brand">SpareHub</div>
            <div class="links">
                <ul>
                    <li><a href="./Homepage.php">Home</a></li>
                    <li><a href="./about.html">About</a></li>
                    <li>
                        <button class="filter-btn" onclick="toggleFilters()" id="navbarFilterBtn">
                            Hide Filters
                        </button>
                    </li>

                    <li>
                        <a href="cart.php" class="cart-icon-link" title="View Cart">
                            <span class="cart-icon">
                                🛒
                                <span id="cart-count" class="cart-count">0</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <main class="results-main">
            <section class="filters-sidebar" id="filtersSidebar">
                <div class="filters-header">
                    <h3>Filters</h3>
                    <button class="clear-filters-btn" onclick="clearAllFilters()">Clear All</button>
                </div>

                <div class="filter-group">
                    <h4>Price Range</h4>
                    <ul class="checkbox-group">
                        <li><label><input type="checkbox" onchange="applyFilters()"> ₹0 - ₹500</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> ₹500 - ₹1000</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> ₹1000 - ₹2000</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> ₹2000+</label></li>
                    </ul>
                </div>

                <div class="filter-group">
                    <h4>Brand</h4>
                    <ul class="checkbox-group">
                        <li><label><input type="checkbox" onchange="applyFilters()"> OEM Parts</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> Aftermarket</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> Performance</label></li>
                    </ul>
                </div>

                <div class="filter-group">
                    <h4>Condition</h4>
                    <ul class="checkbox-group">
                        <li><label><input type="checkbox" onchange="applyFilters()"> New</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> Used</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> Refurbished</label></li>
                    </ul>
                </div>

                <div class="filter-group">
                    <h4>Availability</h4>
                    <ul class="checkbox-group">
                        <li><label><input type="checkbox" onchange="applyFilters()"> In Stock</label></li>
                        <li><label><input type="checkbox" onchange="applyFilters()"> Fast Shipping</label></li>
                    </ul>
                </div>
            </section>

            <section class="results-grid">
                <div class="results-header-bar">
                    <span class="results-count" id="resultsCount">5 results found</span>
                </div>

                <div class="products-grid">
                    <div class="product-card" data-price="650" data-brand="Performance" data-condition="Refurbished"
                        data-availability="In Stock" onclick="window.location.href='partdetails.php?id=1'">
                        <div class="product-image">
                            <img src="../images/airfilter.png" alt="Air Filter">
                        </div>
                        <div class="product-info">
                            <div class="product-brand">Performance</div>
                            <h3 class="product-name">Air Filter</h3>
                            <div class="product-pricing">
                                <span class="current-price">₹650</span>
                            </div>
                            <div class="product-condition">Condition: Refurbished</div>
                            <div class="product-shipping">In Stock</div>
                        </div>
                    </div>

                    <div class="product-card" data-price="800" data-brand="OEM Parts" data-condition="New"
                        data-availability="In Stock" onclick="window.location.href='partdetails.php?id=2'">
                        <div class="product-image">
                            <img src="../images/brakepad.png" alt="Brake Pads">
                        </div>
                        <div class="product-info">
                            <div class="product-brand">OEM Parts</div>
                            <h3 class="product-name">Brake Pads</h3>
                            <div class="product-pricing">
                                <span class="current-price">₹800</span>
                            </div>
                            <div class="product-condition">Condition: New</div>
                            <div class="product-shipping">In Stock</div>
                        </div>
                    </div>

                    <div class="product-card" data-price="2000" data-brand="Performance" data-condition="Refurbished"
                        data-availability="In Stock" onclick="window.location.href='partdetails.php?id=3'">
                        <div class="product-image">
                            <img src="../images/clutchplate.png" alt="Clutchplate">
                        </div>
                        <div class="product-info">
                            <div class="product-brand">Performance</div>
                            <h3 class="product-name">Clutchplate</h3>
                            <div class="product-pricing">
                                <span class="current-price">₹2000</span>
                            </div>
                            <div class="product-condition">Condition: Refurbished</div>
                            <div class="product-shipping">In Stock</div>
                        </div>
                    </div>

                    <div class="product-card" data-price="400" data-brand="OEM Parts" data-condition="New"
                        data-availability="In Stock" onclick="window.location.href='partdetails.php?id=4'">
                        <div class="product-image">
                            <img src="../images/sparkplug.png" alt="Spark Plugs">
                        </div>
                        <div class="product-info">
                            <div class="product-brand">OEM Parts</div>
                            <h3 class="product-name">Spark Plugs</h3>
                            <div class="product-pricing">
                                <span class="current-price">₹400</span>
                            </div>
                            <div class="product-condition">Condition: New</div>
                            <div class="product-shipping">In Stock</div>
                        </div>
                    </div>

                    <div class="product-card" data-price="1500" data-brand="Performance" data-condition="Refurbished"
                        data-availability="In Stock" onclick="window.location.href='partdetails.php?id=5'">
                        <div class="product-image">
                            <img src="../images/headlight.png" alt="HeadLight">
                        </div>
                        <div class="product-info">
                            <div class="product-brand">Performance</div>
                            <h3 class="product-name">HeadLight</h3>
                            <div class="product-pricing">
                                <span class="current-price">₹1500</span>
                            </div>
                            <div class="product-condition">Condition: Refurbished</div>
                            <div class="product-shipping">In Stock</div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>© 2025 AutoParts. All rights reserved.</footer>
    </div>

    <script>
        function toggleFilters() {
            const sidebar = document.getElementById('filtersSidebar');
            const main = document.querySelector('.results-main');
            const btn = document.getElementById('navbarFilterBtn');

            sidebar.classList.toggle('hidden');
            main.classList.toggle('no-filters');
            btn.textContent = sidebar.classList.contains('hidden') ? 'Show Filters' : 'Hide Filters';
        }

        function applyFilters() {
            console.log('Applying filters...');
            const products = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            // Get checked filters
            const priceFilters = getCheckedValues('Price Range');
            const brandFilters = getCheckedValues('Brand');
            const conditionFilters = getCheckedValues('Condition');
            const availabilityFilters = getCheckedValues('Availability');

            console.log('Filters:', { priceFilters, brandFilters, conditionFilters, availabilityFilters });

            products.forEach(product => {
                const price = parseInt(product.dataset.price);
                const brand = product.dataset.brand;
                const condition = product.dataset.condition;
                const availability = product.dataset.availability;

                let show = true;

                // Price filter
                if (priceFilters.length > 0) {
                    show = priceFilters.some(filter => {
                        if (filter === '₹0 - ₹500') return price >= 0 && price <= 500;
                        if (filter === '₹500 - ₹1000') return price >= 500 && price <= 1000;
                        if (filter === '₹1000 - ₹2000') return price >= 1000 && price <= 2000;
                        if (filter === '₹2000+') return price >= 2000;
                        return false;
                    });
                }

                // Brand filter
                if (show && brandFilters.length > 0) {
                    show = brandFilters.includes(brand);
                }

                // Condition filter
                if (show && conditionFilters.length > 0) {
                    show = conditionFilters.includes(condition);
                }

                // Availability filter
                if (show && availabilityFilters.length > 0) {
                    show = availabilityFilters.includes(availability);
                }

                product.style.display = show ? 'block' : 'none';
                if (show) visibleCount++;
            });

            updateCount(visibleCount);
        }

        function getCheckedValues(groupName) {
            const filterGroups = document.querySelectorAll('.filter-group');
            for (let group of filterGroups) {
                if (group.querySelector('h4').textContent === groupName) {
                    const checked = group.querySelectorAll('input:checked');
                    return Array.from(checked).map(cb => cb.parentElement.textContent.trim());
                }
            }
            return [];
        }

        function updateCount(count) {
            document.getElementById('searchInfo').textContent = `${count} Search Results`;
            document.getElementById('resultsCount').textContent = `${count} results found`;
        }

        function clearAllFilters() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);

            const products = document.querySelectorAll('.product-card');
            products.forEach(product => product.style.display = 'block');

            updateCount(5);
        }

            function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            var el = document.getElementById('cart-count');
            if (el) el.textContent = count;
        }
        updateCartCount();
    </script>
</body>

</html>