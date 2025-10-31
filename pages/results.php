<?php
session_start();
include '../database/db.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admindashboard.php");
    exit();
}

// Fetch all parts that are in stock
$stmt = $conn->prepare("SELECT p.*, u.name as seller_name FROM parts p JOIN users u ON p.seller_id = u.user_id WHERE p.stock > 0");
$stmt->execute();
$result = $stmt->get_result();
$parts = $result->fetch_all(MYSQLI_ASSOC);
$total_parts = count($parts);
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
                    <span class="results-count" id="resultsCount"><?php echo $total_parts; ?> results found</span>
                </div>

                <div class="products-grid">
                    <?php foreach ($parts as $part): ?>
                    <div class="product-card" 
                        data-price="<?php echo $part['price']; ?>" 
                        data-condition="New"
                        data-availability="<?php echo $part['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>" 
                        onclick="window.location.href='partdetails.php?id=<?php echo $part['part_id']; ?>'">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($part['images']); ?>" alt="<?php echo htmlspecialchars($part['name']); ?>">
                        </div>
                        <div class="product-info">
                            <div class="product-brand">Seller: <?php echo htmlspecialchars($part['seller_name']); ?></div>
                            <h3 class="product-name"><?php echo htmlspecialchars($part['name']); ?></h3>
                            <div class="product-pricing">
                                <span class="current-price">₹<?php echo number_format($part['price'], 2); ?></span>
                            </div>
                            <div class="product-condition">Stock: <?php echo $part['stock']; ?> units</div>
                            <div class="product-shipping"><?php echo $part['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></div>
                            <?php if (!empty($part['description'])): ?>
                            <div class="product-description"><?php echo htmlspecialchars($part['description']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
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

            updateCount(<?php echo $total_parts; ?>);
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