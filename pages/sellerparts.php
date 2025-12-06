<?php
session_start();
include '../database/db.php';
$seller_id = $_SESSION['user_id'] ?? 0;
// Only allow if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: Login.html");
    exit();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'seller') {
    header("Location: Login.html");
    exit();
}

// ADD PART
if (isset($_POST['add_part'])) {
    $part_id = $_POST['part_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $stock = intval($_POST['stock'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $desc = $_POST['description'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = intval($_POST['year'] ?? 0);

    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? '';
    $oem_number = $_POST['oem_number'] ?? '';
    $images = '';
    if (!empty($_FILES['image']['tmp_name'])) {
        $images = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $images);
    }
    $stmt = $conn->prepare("INSERT INTO parts (part_id,seller_id,name,description,price,stock,brand,model,year,category,status,oem_number,images) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
    "iissdississss",
    $part_id,     // 1. part_id   (int)
    $seller_id,   // 2. seller_id (int)
    $name,        // 3. name      (string)
    $desc,        // 4. description (string)
    $price,       // 5. price     (double)
    $stock,       // 6. stock     (int)
    $brand,       // 7. brand     (string)
    $model,       // 8. model     (string)
    $year,        // 9. year      (int)
    $category,    // 10. category (string)
    $status,      // 11. status   (string)
    $oem_number,  // 12. oem_number (string)
    $images       // 13. images   (string)
);

    $stmt->execute();
    $stmt->close();
    header("Location: sellerparts.php");
    exit();
}

// EDIT PART
if (isset($_POST['edit_part'])) {
    $partid = $_POST['part_id'];
    $name = $_POST['name'] ?? '';
    $stock = intval($_POST['stock'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $desc = $_POST['description'] ?? '';
    $images = $_POST['old_image'] ?? '';
    if (!empty($_FILES['image']['tmp_name'])) {
        $images = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $images);
    }
    $stmt = $conn->prepare("UPDATE parts SET name=?, stock=?, price=?, description=?, images=? WHERE part_id=? AND seller_id=?");
    $stmt->bind_param("sidssii", $name, $stock, $price, $desc, $images, $partid, $seller_id);
    $stmt->execute();
    $stmt->close();
    header("Location: sellerparts.php");
    exit();
}

// DELETE PART
if (isset($_GET['delete'])) {
    $partid = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM parts WHERE part_id=? AND seller_id=?");
    $stmt->bind_param("ii", $partid, $seller_id);
    $stmt->execute();
    $stmt->close();
    header("Location: sellerparts.php");
    exit();
}

// FETCH PARTS
$result = $conn->prepare("SELECT part_id, name, stock, price, images, description FROM parts WHERE seller_id=?");
$result->bind_param("i", $seller_id);
$result->execute();
$res = $result->get_result();

// Calculate stats
$totalParts = $res->num_rows;
$totalRevenue = 16500.00;
$lowStockCount = 0;
$parts = [];
while ($row = $res->fetch_assoc()) {
    $parts[] = $row;
    if ($row['stock'] < 5)
        $lowStockCount++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Parts Inventory | SpareHub</title>
    <link rel="stylesheet" href="../styles/sellerparts.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <span style="color:#ff6b35; font-weight:700; font-size:28px;"> ⚙️ SpareHub</span>
            </div>
            <nav class="sidebar-nav">
                <a href="results.php"><span style="font-size:18px;">🏠</span> Catalogue</a>
                <a href="sellerdashboard.php"><span style="font-size:18px;">📊</span> Dashboard</a>
                <a href="sellerprofile.php"><span style="font-size:18px;">👤</span> Profile</a>
                <a href="sellerparts.php" class="active"><span style="font-size:18px;">⚙️</span> My Parts</a>
            </nav>
            <div class="sidebar-footer">
                <form action="logout.php" method="post">
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1>My Parts Inventory</h1>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-icon">📦</span>
                    <span class="stat-label">Total Parts</span>
                    <span class="stat-value"><?= $totalParts ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">🔄</span>
                    <span class="stat-label">Parts Sold</span>
                    <span class="stat-value">32</span>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📈</span>
                    <span class="stat-label">Revenue</span>
                    <span class="stat-value">₹<?= number_format($totalRevenue, 2) ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">⚠️</span>
                    <span class="stat-label">Low Stock</span>
                    <span class="stat-value"><?= $lowStockCount ?></span>
                </div>
            </div>

            <!-- Show Analytics Button -->
            <div style="text-align:center; margin: 30px 0 20px 0;">
                <button id="toggleAnalytics" class="analytics-btn">📊 Show Analytics</button>
            </div>

            <!-- Analytics Section (Hidden by default) -->
            <div id="analyticsSection" style="display:none; margin-top: 30px;">
                <h2 style="text-align: center; color: #1f2937; margin-bottom: 20px;">Parts Analytics</h2>

                <!-- Toggle buttons for chart view -->
                <div class="chart-toggle">
                    <button id="stockBtn" class="chart-toggle-btn active">📦 Stock Levels</button>
                    <button id="salesBtn" class="chart-toggle-btn">💰 Pricing</button>
                </div>

                <!-- Chart Canvas -->
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Parts Inventory Section -->
            <h2>Parts Inventory</h2>

            <?php if (count($parts) > 0): ?>
                <div class="table-responsive">
                    <table class="parts-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($parts as $row): ?>
                                <tr>
                                    <form method="POST" enctype="multipart/form-data">
                                        <td><?= $row['part_id'] ?></td>
                                        <td>
                                            <?php if ($row['images']): ?>
                                                <img src="<?= htmlspecialchars($row['images']) ?>" class="part-img"
                                                    alt="Part Image">
                                            <?php endif; ?>
                                            <input type="file" name="image" style="font-size:0.85rem;">
                                            <input type="hidden" name="old_image"
                                                value="<?= htmlspecialchars($row['images']) ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"
                                                required>
                                        </td>
                                        <td>
                                            <input type="number" name="stock" value="<?= $row['stock'] ?>" min="0" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" required>
                                        </td>
                                        <td>
                                            <input type="text" name="description"
                                                value="<?= htmlspecialchars($row['description']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="hidden" name="part_id" value="<?= $row['part_id'] ?>">
                                            <button type="submit" name="edit_part" class="btn-edit">Edit</button>
                                            <a href="sellerparts.php?delete=<?= $row['part_id'] ?>" class="btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this part?');">Delete</a>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📦 No parts yet</p>
                    <p>Start by adding your first part to the inventory</p>
                </div>
            <?php endif; ?>

            <!-- Add New Part Form -->
            <h3>Add New Part</h3>
            <form class="add-part-form" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Part Name" required>
                <input type="text" name="part_id" placeholder="Part-id" required>
                <input type="text" name="stock" min="0" placeholder="Stock Quantity" required>
                <input type="text" step="0.01" name="price" placeholder="Price (₹)" required>
                <input type="text" name="model" placeholder="Model[Part model]" required>
                <input type="text" name="description" placeholder="Description" required>
                <input type="text" name="brand" placeholder="Brand[Oem,Aftermarket]" required>
                <input type="text" name="oem_number" placeholder="oem_number" required>
                <select name="status" id="status" required>
                    <option value="new">New</option>
                    <option value="used">Used</option>
                    <option value="refurbished">Refurbished</option>
                </select>

                <input type="text" name="year" placeholder="Year" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="file" name="image" accept="image/*">
                <button type="submit" name="add_part" class="btn-add">Add Part</button>
            </form>

            <!-- Footer -->
            <footer class="page-footer">
                <a href="#">About</a>
                <a href="#">Contact</a>
                <a href="#">Help</a>
            </footer>
        </main>
    </div>

    <!-- Pass PHP data to JavaScript -->
    <script>
        window.partsChartData = <?php echo json_encode($parts); ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="partanalytics.js"></script>
</body>

</html>