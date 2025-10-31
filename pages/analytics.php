<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - SpareHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/analytics.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="analytics.js" defer></script>
</head>
<body>
    <div class="sidebar">
        <div class="brand-logo">SpareHub</div>
        <nav class="nav-menu">
            <a href="request.php" class="nav-item"><i class="fas fa-boxes"></i> <span>request</span></a>
            <a href="admindashboard.php" class="nav-item"><i class="fas fa-boxes"></i> <span>Inventory</span></a>
            <a href="analytics.php" class="nav-item active"><i class="fas fa-chart-line"></i> <span>Analytics</span></a>
            <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>
    <div class="main-content">
        <header class="topbar">
            <h1>Analytics Dashboard</h1>
            <div class="user-info">
                <span>Admin User</span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>
        <section class="analytics-grid">
            <div class="card stats-card">
                <h3>Total Revenue</h3>
                <p class="stat-value">₹1,25,000</p>
                <small>+12% from last month</small>
                <i class="fas fa-rupee-sign icon"></i>
            </div>
            <div class="card stats-card">
                <h3>Total Orders</h3>
                <p class="stat-value">342</p>
                <small>+8% from last month</small>
                <i class="fas fa-shopping-cart icon"></i>
            </div>
            <div class="card stats-card">
                <h3>Top Selling Part</h3>
                <p class="stat-value">Brake Pad Set</p>
                <small>Sold 87 units</small>
                <i class="fas fa-star icon"></i>
            </div>
            <div class="card stats-card">
                <h3>Low Stock</h3>
                <p class="stat-value">3 items</p>
                <small>Needs replenishment</small>
                <i class="fas fa-exclamation-triangle icon"></i>
            </div>
            <div class="card chart-container">
                <h3>Monthly Sales (Last 6 Months)</h3>
                <canvas id="salesChart"></canvas>
            </div>
            <div class="card chart-container">
                <h3>Revenue by Product Category</h3>
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="card chart-container">
                <h3>Inventory Status</h3>
                <canvas id="inventoryChart"></canvas>
            </div>
            <div class="card chart-container">
                <h3>Order Fulfillment Time</h3>
                <canvas id="fulfillmentChart"></canvas>
            </div>
        </section>
        <section class="quick-actions card">
            <h3>Quick Actions</h3>
            <a href="admindashboard.php" class="action-btn secondary"><i class="fas fa-arrow-left"></i> Back to Admin Panel</a>
        </section>
    </div>
</body>
</html>