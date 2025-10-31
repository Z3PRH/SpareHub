<?php
session_start();
// Only allow if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: Login.html");
    exit();
}


$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : $_SESSION['user_id'];
if ($_SESSION['user_id'] != $seller_id) {
    // User can only view their own data, not others'
    header("Location: unauthorized.html"); // or just exit()
    exit();
}

include '../database/db.php';
$seller_id = $_SESSION['user_id'];
$sellerName = $_SESSION['name'] ?? 'Seller';

// Fetch total parts
$totalPartsQuery = $conn->prepare("SELECT COUNT(*) as total FROM parts WHERE seller_id = ?");
$totalPartsQuery->bind_param("i", $seller_id);
$totalPartsQuery->execute();
$totalParts = $totalPartsQuery->get_result()->fetch_assoc()['total'];

// Fetch low stock parts (stock < 5)
$lowStockQuery = $conn->prepare("SELECT COUNT(*) as low_stock FROM parts WHERE seller_id = ? AND stock < 5");
$lowStockQuery->bind_param("i", $seller_id);
$lowStockQuery->execute();
$lowStock = $lowStockQuery->get_result()->fetch_assoc()['low_stock'];

// Fetch total revenue (you'll need an orders table for real data)
$totalRevenue = 16500; // Placeholder - replace with actual query
$partsSold = 32; // Placeholder - replace with actual query

// Fetch recent parts
$recentPartsQuery = $conn->prepare("SELECT part_id, name, stock, price, images FROM parts WHERE seller_id = ? ORDER BY part_id DESC LIMIT 3");
$recentPartsQuery->bind_param("i", $seller_id);
$recentPartsQuery->execute();
$recentParts = $recentPartsQuery->get_result();

// Fetch low stock alerts
$lowStockAlerts = $conn->prepare("SELECT part_id, name, stock FROM parts WHERE seller_id = ? AND stock < 5 ORDER BY stock ASC LIMIT 5");
$lowStockAlerts->bind_param("i", $seller_id);
$lowStockAlerts->execute();
$alerts = $lowStockAlerts->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpareHub - Seller Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/sellerdashboard.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <span>⚙️</span> SpareHub
            </div>
            <nav>
                <a href="results.php">🏠 Catalogue</a>
                <a href="sellerdashboard.php" class="active">📊 Dashboard</a>
                <a href="sellerprofile.php">👤 Profile</a>
                <a href="sellerparts.php">⚙️ My Parts</a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="logout.php" style="width: 100%;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1>Dashboard Overview</h1>
                    <p class="subtitle">Welcome back, <?php echo htmlspecialchars($sellerName); ?>! 👋</p>
                </div>
                <div class="user-info">
                    <div class="user-avatar"><?php echo htmlspecialchars(substr($sellerName, 0, 1)); ?></div>
                    <span><?php echo htmlspecialchars($sellerName); ?></span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon orange">📦</div>
                        <div>
                            <div class="stat-label">Total Parts</div>
                            <div class="stat-value"><?php echo $totalParts; ?></div>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="stat-change positive">+12% from last month</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon blue">📋</div>
                        <div>
                            <div class="stat-label">Parts Sold</div>
                            <div class="stat-value"><?php echo $partsSold; ?></div>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="stat-change positive">+8% from last month</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon green">💰</div>
                        <div>
                            <div class="stat-label">Total Revenue</div>
                            <div class="stat-value">₹<?php echo number_format($totalRevenue); ?></div>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="stat-change positive">+15% from last month</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon red">⚠️</div>
                        <div>
                            <div class="stat-label">Low Stock Alert</div>
                            <div class="stat-value"><?php echo $lowStock; ?></div>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="stat-change negative">Needs attention</span>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Parts -->
                <div class="card">
                    <div class="card-header">
                        <h2>Recent Parts</h2>
                        <a href="sellerparts.php" class="view-all">View All →</a>
                    </div>
                    <div class="card-content">
                        <?php if ($recentParts->num_rows > 0): ?>
                            <div class="parts-list">
                                <?php while ($part = $recentParts->fetch_assoc()): ?>
                                <div class="part-item">
                                    <div class="part-image-small">
                                        <?php if ($part['images']): ?>
                                            <img src="<?php echo htmlspecialchars($part['images']); ?>" alt="<?php echo htmlspecialchars($part['name']); ?>">
                                        <?php else: ?>
                                            <span class="placeholder-icon">⚙️</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="part-details">
                                        <div class="part-name"><?php echo htmlspecialchars($part['name']); ?></div>
                                        <div class="part-meta">
                                            <span class="part-price">₹<?php echo number_format($part['price'], 2); ?></span>
                                            <span class="part-stock">Stock: <?php echo $part['stock']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-small">
                                <p>No parts listed yet</p>
                                <a href="sellerparts.php" class="btn btn-primary">Add Your First Part</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="card">
                    <div class="card-header">
                        <h2>Low Stock Alerts</h2>
                        <span class="badge-alert"><?php echo $lowStock; ?></span>
                    </div>
                    <div class="card-content">
                        <?php if ($alerts->num_rows > 0): ?>
                            <div class="alerts-list">
                                <?php while ($alert = $alerts->fetch_assoc()): ?>
                                <div class="alert-item">
                                    <div class="alert-icon">⚠️</div>
                                    <div class="alert-details">
                                        <div class="alert-name"><?php echo htmlspecialchars($alert['name']); ?></div>
                                        <div class="alert-stock">Only <?php echo $alert['stock']; ?> left in stock</div>
                                    </div>
                                    <a href="sellerparts.php" class="btn-restock">Restock</a>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-small">
                                <span class="success-icon">✅</span>
                                <p>All parts are well stocked!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="actions-grid">
                    <a href="sellerparts.php" class="action-card">
                        <div class="action-icon">➕</div>
                        <div class="action-title">Add New Part</div>
                        <div class="action-description">List a new spare part</div>
                    </a>
                    <a href="sellerparts.php" class="action-card">
                        <div class="action-icon">📦</div>
                        <div class="action-title">Manage Inventory</div>
                        <div class="action-description">Update parts and stock</div>
                    </a>
                    <a href="sellerprofile.php" class="action-card">
                        <div class="action-icon">👤</div>
                        <div class="action-title">Edit Profile</div>
                        <div class="action-description">Update your information</div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>