<?php
session_start();

// Redirect to login if not authenticated or not a buyer
if(!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'buyer') {
    header("Location: Login.html");
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

// Handle profile update
$updateMessage = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newName = trim($_POST['fullname']);
    $newEmail = trim($_POST['email']);
    $newPhone = trim($_POST['phone']);
    
    // Update in database
    $updateSql = "UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
    $updateStmt = $mysqli->prepare($updateSql);
    $updateStmt->bind_param("sssi", $newName, $newEmail, $newPhone, $buyerId);
    
    if($updateStmt->execute()) {
        $_SESSION['buyername'] = $newName;
        $buyerName = $newName;
        $email = $newEmail;
        $phone = $newPhone;
        $updateMessage = "Profile updated successfully!";
    } else {
        $updateMessage = "Error updating profile.";
    }
    $updateStmt->close();
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
    <link rel="stylesheet" href="../styles/buyerprofile.css">
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
                <a href="buyer.php">📊 Dashboard</a>
                <a href="buyerprofile.php" class="active">👤 Profile</a>
                <a href="settings.php">⚙️ Settings</a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="logout.php" style="width: 100%;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <a href="buyer.php" class="back-link">← Back to Dashboard</a>

            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar-large"><?php echo htmlspecialchars(substr($buyerName, 0, 1)); ?></div>
                    <h2><?php echo htmlspecialchars($buyerName); ?></h2>
                    <p>Buyer Account</p>
                </div>

                <!-- Profile Content -->
                <div class="profile-content">
                    <?php if($updateMessage): ?>
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
                    <form method="POST" action="">
                        <div class="form-section">
                            <h3>Edit Profile</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fullname">Full Name</label>
                                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($buyerName); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="+91 XXXXX XXXXX">
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
                        <button class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) { window.location.href='delete_account.php'; }">Delete Account</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>