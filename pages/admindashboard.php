<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    // Not logged in as admin means redirect to login
    header("Location: Login.html");
    exit();
}

// Restrict admin pages to certain paths
$allowedPages = ['admindashboard.php', 'logout.php'];
$currentPage = basename($_SERVER['PHP_SELF']);

if (!in_array($currentPage, $allowedPages)) {
    header("Location: admindashboard.php");
    exit();
}
?>

<?php
include '../database/db.php';

// Handle price update and update status based on stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'])) {
    $id = $_POST['part_id'];
    $new_price = $_POST['price'];

    // Fetch current stock to determine status
    $check_sql = "SELECT stock FROM parts WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $part = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check_stmt);

    $new_status = ($part['stock'] >= 50) ? 'In Stock' : 'Low Stock';

    // Update price and status
    $sql = "UPDATE parts SET price = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "dsi", $new_price, $new_status, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$success) {
        die("Error updating record: " . mysqli_error($conn));
    }
}

// Fetch inventory data with error handling
$sql = "SELECT * FROM parts";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error fetching parts: " . mysqli_error($conn));
}
$parts = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate Total Value
$total_value = 0;
foreach ($parts as $part) {
    $total_value += $part['price'] * $part['stock'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SpareHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/admin.css">
</head>

<body>
    <div class="sidebar">
        <div class="brand-logo">SpareHub</div>
        <nav class="nav-menu">
            <a href="request.php" class="nav-item active"><i class="fas fa-boxes"></i> <span>Requests</span></a>
            <a href="admindashboard.php" class="nav-item active"><i class="fas fa-boxes"></i> <span>Inventory</span></a>
            <a href="analytics.php" class="nav-item"><i class="fas fa-chart-line"></i> <span>Analytics</span></a>
            <a href="logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>
    <div class="main-content">
        <header class="topbar">
            <h1>Admin Dashboard</h1>
            <div class="user-info">
                <span>Admin User</span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>
        <section class="dashboard-grid">
            <div class="card stats-card">
                <h3>Total Parts</h3>
                <p class="stat-value"><?php echo count($parts); ?></p>
                <i class="fas fa-cogs icon"></i>
            </div>
            <div class="card stats-card">
                <h3>In Stock</h3>
                <p class="stat-value"><?php echo array_sum(array_column($parts, 'stock')); ?></p>
                <i class="fas fa-check-circle icon"></i>
            </div>
            <div class="card stats-card">
                <h3>Low Stock</h3>
                <p class="stat-value"><?php echo count(array_filter($parts, fn($p) => $p['stock'] < 10)); ?></p>
                <i class="fas fa-exclamation-triangle icon"></i>
            </div>
            <div class="card stats-card">
                <h3>Total Value</h3>
                <p class="stat-value">₹<?php echo number_format($total_value, 2); ?></p>
                <i class="fas fa-rupee-sign icon"></i>
            </div>
        </section>
        <section class="inventory-section card">
            <div class="section-header">
                <h2>Spare Parts Inventory</h2>
                <button class="add-btn" onclick="alert('Add new product functionality to be implemented.')"><i
                        class="fas fa-plus"></i> Add New Part</button>
            </div>
            <div class="table-container">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Part Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parts as $part): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($part['name']); ?></td>
                                <td>
                                    <form method="POST" action="admindashboard.php">
                                        <input type="hidden" name="id" value="<?php echo $part['part_id']; ?>">
                                        <input type="number" name="price" value="<?php echo $part['price']; ?>" step="0.01"
                                            min="0" style="width: 100px;">
                                        <button type="submit" name="update_price" class="action-btn edit"><i
                                                class="fas fa-save"></i></button>
                                    </form>
                                </td>
                                <td><?php echo $part['stock']; ?></td>
                                <td>
                                    <?php
                                    $status = ($part['stock'] >= 50) ? 'In Stock' : 'Low Stock';
                                    ?>
                                    <span class="status <?php echo strtolower(str_replace(' ', '-', $status)); ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <button class="action-btn edit"
                                        onclick="alert('Edit functionality to be implemented.')"><i
                                            class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"
                                        onclick="if (confirm('Are you sure?')) alert('Deleted!')"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>