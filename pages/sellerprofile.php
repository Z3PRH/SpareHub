<?php
session_start();
if ($_SESSION['role'] === 'seller'): ?>
<?php endif; 

// Redirect to login if not authenticated or not a seller
// Only allow if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: Login.html");
    exit();
}
// Check GET/POST or route parameter for correct seller ID
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : $_SESSION['user_id'];
if ($_SESSION['user_id'] != $seller_id) {
    // User can only view their own data, not others'
    header("Location: unauthorized.html"); // or just exit()
    exit();
}


$buyerName = isset($_SESSION['buyername']) ? $_SESSION['buyername'] : '';
$buyerId = $_SESSION['user_id'];

$mysqli = new mysqli('localhost', 'root', '', 'sparehub');
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

// Fetch user profile from users table
$sql = "SELECT user_id, email, phone, created_at FROM users WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $buyerId);
$stmt->execute();
$result = $stmt->get_result();
$userProfile = $result->fetch_assoc();
$stmt->close();

$email = $userProfile['email'] ?? '';
$phone = $userProfile['phone'] ?? '';
$createdAt = $userProfile['created_at'] ?? '';

// Handle profile update and credential upload
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newName = trim($_POST['fullname']);
    $newEmail = trim($_POST['email']);
    $newPhone = trim($_POST['phone']);

    // Update in database
    $updateSql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?";
    $updateStmt = $mysqli->prepare($updateSql);
    $updateStmt->bind_param("sssi", $newName, $newEmail, $newPhone, $buyerId);

    if ($updateStmt->execute()) {
        $_SESSION['buyername'] = $newName;
        $buyerName = $newName;
        $email = $newEmail;
        $phone = $newPhone;
        $updateMessage = "Profile updated successfully!";
    } else {
        $updateMessage = "Error updating profile.";
    }
    $updateStmt->close();

    // Credential file upload logic (image/pdf)
    if (!empty($_FILES['credential']['tmp_name'])) {
        $targetDir = "uploads/credentials/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $credFilename = time() . "_" . basename($_FILES["credential"]["name"]);
        $targetFile = $targetDir . $credFilename;
        move_uploaded_file($_FILES["credential"]["tmp_name"], $targetFile);

        // Insert into seller_requests table
        $credSql = "INSERT INTO seller_requests (user_id, credential_filename, credential_original_name, status) VALUES (?, ?, ?, 'pending')";
        $credStmt = $mysqli->prepare($credSql);
        $credStmt->bind_param("iss", $buyerId, $credFilename, $_FILES["credential"]["name"]);
        $credStmt->execute();
        $credStmt->close();

        $updateMessage .= " | Credential uploaded. Seller request sent for admin approval.";
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpareHub - Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/userprofile.css">
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
                <a href="sellerdashboard.php">📊 Dashboard</a>
                <a href="sellerprofile.php" class="active">👤 Profile</a>
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

            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar-large"><?php echo htmlspecialchars(substr($buyerName, 0, 1)); ?></div>
                    <h2><?php echo htmlspecialchars($buyerName); ?></h2>
                    <p>Buyer Account</p>
                </div>

                <!-- Profile Content -->
                <div class="profile-content">
                    <?php if ($updateMessage): ?>
                        <div class="success-message"><?php echo htmlspecialchars($updateMessage); ?></div>
                    <?php endif; ?>

                    <!-- Account Information -->
                    <div class="form-section">
                        <h3>Account Information</h3>
                        <div class="info-grid">
                            <div class="info-card">
                                <label>User ID</label>
                                <p><?php echo htmlspecialchars($buyerId); ?></p>
                            </div>
                            <div class="info-card">
                                <label>Account Created</label>
                                <p><?php echo $createdAt ? date('M d, Y', strtotime($createdAt)) : 'N/A'; ?></p>
                            </div>
                            <div class="info-card">
                                <label>Account Status</label>
                                <p style="color: #10b981;">Active</p>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Form -->
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-section">
                            <h3>Edit Profile</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fullname">Full Name</label>
                                    <input type="text" id="fullname" name="fullname"
                                        value="<?php echo htmlspecialchars($buyerName); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email"
                                        value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($phone); ?>" placeholder="+91 XXXXX XXXXX">
                            </div>

                            <!-- Credentials column/image upload -->
                            <div class="form-group">
                                <label for="credential">Business Credential (Image/PDF)</label>
                                <input type="file" name="credential" id="credential" accept="image/*,application/pdf">
                                <div style="font-size: 0.85em; color: #64748b; margin-top: 4px;">
                                    For seller approval, upload a business license or permit.
                                </div>
                            </div>

                            <div class="btn-group">
                                <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>

                    <!-- Danger Zone -->
                    <div class="danger-zone">
                        <h3>⚠️ Danger Zone</h3>
                        <p>Once you delete your account, there is no going back. Please be certain.</p>
                        <button class="btn btn-danger"
                            onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) { window.location.href='delete_account.php'; }">Delete
                            Account</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
