<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - SpareHub</title>
    <link rel="stylesheet" href="../styles/about.css">
    <link rel="stylesheet" href="../styles/analytics.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../pages/analytics.js" defer></script>
</head>
<body>

    <div class="admin-main">
        <div class="admin-content">
            <h2>SpareHub Analytics Dashboard</h2>
            
            <div class="analytics-grid">
                <!-- Quick Stats Cards -->
                <div class="stats-card">
                    <h3>Total Revenue</h3>
                    <p>₹1,25,000</p>
                    <small>+12% from last month</small>
                </div>
                
                <div class="stats-card">
                    <h3>Total Orders</h3>
                    <p>342</p>
                    <small>+8% from last month</small>
                </div>
                
                <div class="stats-card">
                    <h3>Top Selling Part</h3>
                    <p>Brake Pad Set</p>
                    <small>Sold 87 units</small>
                </div>
                
                <div class="stats-card">
                    <h3>Low Stock</h3>
                    <p>3 items</p>
                    <small>Needs replenishment</small>
                </div>
                
                <!-- Chart 1: Monthly Sales -->
                <div class="chart-container">
                    <h3>Monthly Sales (Last 6 Months)</h3>
                    <canvas id="salesChart"></canvas>
                </div>
                
                <!-- Chart 2: Product Category Distribution -->
                <div class="chart-container">
                    <h3>Revenue by Product Category</h3>
                    <canvas id="categoryChart"></canvas>
                </div>
                
                <!-- Chart 3: Inventory Status -->
                <div class="chart-container">
                    <h3>Inventory Status</h3>
                    <canvas id="inventoryChart"></canvas>
                </div>
                
                <!-- Chart 4: Order Fulfillment -->
                <div class="chart-container">
                    <h3>Order Fulfillment Time</h3>
                    <canvas id="fulfillmentChart"></canvas>
                </div>
            </div>
            
            <a href="admin.php" class="back-btn">← Back to Admin Panel</a>
        </div>
    </div>
</body>
</html>