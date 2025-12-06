<?php
session_start();

// Redirect to login if not authenticated or not a buyer
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'buyer') {
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

// Check if user already has a pending or approved seller request
$requestCheckSql = "SELECT status FROM seller_requests WHERE user_id = ? ORDER BY request_time DESC LIMIT 1";
$requestCheckStmt = $mysqli->prepare($requestCheckSql);
$requestCheckStmt->bind_param("i", $buyerId);
$requestCheckStmt->execute();
$requestCheckResult = $requestCheckStmt->get_result();
$existingRequest = $requestCheckResult->fetch_assoc();
$requestCheckStmt->close();

$sellerRequestStatus = $existingRequest['status'] ?? null;

// Handle profile update
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
}

// Handle seller request submission
$requestMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_seller_request'])) {
    
    // Check if user already has a pending or approved request
    if ($sellerRequestStatus === 'pending' || $sellerRequestStatus === 'approved') {
        $requestMessage = "You already have a " . $sellerRequestStatus . " seller request.";
    } 
    // Validate file upload
    elseif (empty($_FILES['credential']['tmp_name'])) {
        $requestMessage = "Please upload a business credential file.";
    } 
    else {
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
        $fileType = $_FILES['credential']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $requestMessage = "Invalid file type. Please upload an image or PDF file.";
        }
        // Validate file size (5MB max)
        elseif ($_FILES['credential']['size'] > 5242880) {
            $requestMessage = "File size too large. Maximum size is 5MB.";
        }
        else {
            // Create uploads directory if it doesn't exist
            $targetDir = "uploads/credentials/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Generate unique filename
            $fileExtension = pathinfo($_FILES["credential"]["name"], PATHINFO_EXTENSION);
            $credFilename = time() . "_" . $buyerId . "." . $fileExtension;
            $targetFile = $targetDir . $credFilename;

            // Move uploaded file
            if (move_uploaded_file($_FILES["credential"]["tmp_name"], $targetFile)) {
                // Insert into seller_requests table
                $credSql = "INSERT INTO seller_requests (user_id, credential_filename, credential_original_name, status) VALUES (?, ?, ?, 'pending')";
                $credStmt = $mysqli->prepare($credSql);
                $credStmt->bind_param("iss", $buyerId, $credFilename, $_FILES["credential"]["name"]);
                
                if ($credStmt->execute()) {
                    $requestMessage = "✓ Seller request submitted successfully! Your request is pending admin approval.";
                    $sellerRequestStatus = 'pending'; // Update status to show pending message
                } else {
                    $requestMessage = "Error submitting seller request. Please try again.";
                    // Delete uploaded file if database insert fails
                    unlink($targetFile);
                }
                $credStmt->close();
            } else {
                $requestMessage = "Error uploading file. Please try again.";
            }
        }
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
    <style>
        .success-message {
            background-color: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #10b981;
        }
        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #ef4444;
        }
        .info-message {
            background-color: #fef3c7;
            color: #92400e;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #f59e0b;
        }
    </style>
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
                <a href="buyerdashboard.php">📊 Dashboard</a>
                <a href="buyerprofile.php" class="active">👤 Profile</a>
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

                    <?php if ($requestMessage): ?>
                        <div class="<?php echo (strpos($requestMessage, '✓') !== false) ? 'success-message' : 'error-message'; ?>">
                            <?php echo htmlspecialchars($requestMessage); ?>
                        </div>
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

                            <div class="btn-group">
                                <button type="submit" name="update_profile" class="btn btn-primary">Save
                                    Changes</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>

                    <!-- Seller Request Section -->
                    <div class="form-section">
                        <h3>🏪 Request Seller Account</h3>
                        
                        <?php if ($sellerRequestStatus === 'pending'): ?>
                            <div class="info-message">
                                ⏳ Your seller request is pending admin approval.
                            </div>
                        <?php elseif ($sellerRequestStatus === 'approved'): ?>
                            <div class="success-message">
                                ✓ Your seller request has been approved! You now have seller privileges.
                            </div>
                        <?php elseif ($sellerRequestStatus === 'denied'): ?>
                            <div class="error-message">
                                ✗ Your previous seller request was denied. You can submit a new request.
                            </div>
                        <?php endif; ?>

                        <?php if ($sellerRequestStatus !== 'pending' && $sellerRequestStatus !== 'approved'): ?>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="credential">Business Credential (Image/PDF) *</label>
                                    <input type="file" name="credential" id="credential" accept="image/*,application/pdf" required>
                                    <div style="font-size: 0.85em; color: #64748b; margin-top: 4px;">
                                        Upload a business license, permit, or other official document to verify your business.
                                    </div>
                                </div>
                                <button type="submit" name="submit_seller_request" class="btn btn-primary">
                                    Submit Seller Request
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Danger Zone -->
                    <div class="danger-zone">
                        <h3>⚠️ Danger Zone</h3>
                        <p>Once you delete your account, there is no going back. Please be certain.</p>
                        <button class="btn btn-danger"
                            onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) { window.location.href='../database/delete.php'; }">Delete
                            Account</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>