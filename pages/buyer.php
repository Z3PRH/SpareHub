<?php
session_start();

// Redirect to login if not authenticated or not a buyer
if(!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'buyer') {
    header("Location: Login.html");
    exit();
}

$buyerName = isset($_SESSION['buyername']) ? $_SESSION['buyername'] : '';
$buyerId = $_SESSION['user_id']; // Set on login from users.userid


$mysqli = new mysqli('localhost', 'root', '', 'sparehub');
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

// Fetch all orders for this buyer
$sql = "SELECT order_id, order_date, status, total_amount, shipping_address, tracking_number FROM orders WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $buyerId);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Compute summary values
$totalPurchases = count($orders);
$totalSpent = 0;
$pendingOrders = 0;
foreach($orders as $order) {
    $status = strtolower($order['status']);
    // Count as spent if status is completed, delivered, or processing
    if(in_array($status, ['completed', 'delivered', 'processing'])) {
        $totalSpent += floatval($order['total_amount']);
    }
    if($status === 'pending') $pendingOrders++;
}
$avgOrderValue = $totalPurchases > 0 ? round($totalSpent / $totalPurchases, 2) : 0;

// Function to get status badge class
function getStatusClass($status) {
    $status = strtolower($status);
    if ($status === 'completed') return 'status-completed';
    if ($status === 'pending') return 'status-pending';
    if ($status === 'processing') return 'status-processing';
    if ($status === 'cancelled') return 'status-cancelled';
    return 'status-processing';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpareHub - Buyer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/buyer.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <span>⚙️</span> SpareHub
            </div>
            <nav>
                <a href="Homepage.php">🏠 Homepage</a>
                <a href="buyer.php" class="active">📊 Dashboard</a>
                <a href="buyerprofile.php">👤 Profile</a>
                <a href="#settings">⚙️ Settings</a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="logout.php" style="width: 100;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>Buyer Dashboard</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($buyerName); ?></span>
                    <div class="user-avatar"><?php echo htmlspecialchars(substr($buyerName, 0, 2)); ?></div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="card-icon">📦</div>
                    <div class="card-label">Total Purchases</div>
                    <div class="card-value"><?php echo $totalPurchases; ?></div>
                </div>
                <div class="summary-card">
                    <div class="card-icon">💰</div>
                    <div class="card-label">Total Spent</div>
                    <div class="card-value">₹<?php echo number_format($totalSpent, 2); ?></div>
                </div>
                <div class="summary-card">
                    <div class="card-icon">⏳</div>
                    <div class="card-label">Pending Orders</div>
                    <div class="card-value"><?php echo $pendingOrders; ?></div>
                </div>
                <div class="summary-card">
                    <div class="card-icon">📊</div>
                    <div class="card-label">Avg Order Value</div>
                    <div class="card-value">₹<?php echo number_format($avgOrderValue, 2); ?></div>
                </div>
            </div>

            <!-- Transactions Section -->
            <div class="section-header">
                <h2>Previous Transactions</h2>
            </div>

            <?php if($totalPurchases > 0): ?>
                <div class="transactions-container">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th>Shipping Address</th>
                                    <th>Tracking #</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                                    <td><span class="status-badge <?php echo getStatusClass($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                    <td>₹<?php echo htmlspecialchars(number_format($row['total_amount'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tracking_number'] ?: 'N/A'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="transactions-container">
                    <div class="empty-state">
                        <div class="empty-state-icon">📦</div>
                        <h3>No Orders Yet</h3>
                        <p>You haven't made any purchases yet. Start shopping for spare parts!</p>
                        <a href="Homepage.html" class="empty-state-btn">Start Shopping</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Footer Links -->
            <div class="footer-links">
                <a href="about.html">About</a>
                <a href="contact.html">Contact</a>
            </div>
        </main>
    </div>
</body>
</html>