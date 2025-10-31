<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'admin') {
    header("Location: Login.html");
    exit();
}

$mysqli = new mysqli('localhost', 'root', 'ullivada', 'sparehub');
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

// Handle Approve/Deny actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reqId = intval($_POST['req_id']);
    if (isset($_POST['approve'])) {
        $stmt = $mysqli->prepare("UPDATE seller_requests SET status='approved', reviewed_time=NOW() WHERE request_id=?");
        $stmt->bind_param("i", $reqId);
        $stmt->execute();
        $stmt->close();

        // Get user id from request
        $stmt = $mysqli->prepare("SELECT user_id FROM seller_requests WHERE request_id=?");
        $stmt->bind_param("i", $reqId);
        $stmt->execute();
        $stmt->bind_result($uid);
        $stmt->fetch();
        $stmt->close();

        if ($uid) {
            $ustmt = $mysqli->prepare("UPDATE users SET role='seller' WHERE user_id=?");
            $ustmt->bind_param("i", $uid);
            $ustmt->execute();
            $ustmt->close();
        }
    } elseif (isset($_POST['deny'])) {
        $reason = trim($_POST['denial_reason']);
        $stmt = $mysqli->prepare("UPDATE seller_requests SET status='denied', denial_reason=?, reviewed_time=NOW() WHERE request_id=?");
        $stmt->bind_param("si", $reason, $reqId);
        $stmt->execute();
        $stmt->close();
    }
}

// Summary statistics
$totalRequests = $mysqli->query("SELECT COUNT(*) FROM seller_requests")->fetch_row()[0];
$pendingRequests = $mysqli->query("SELECT COUNT(*) FROM seller_requests WHERE status='pending'")->fetch_row()[0];
$approvedRequests = $mysqli->query("SELECT COUNT(*) FROM seller_requests WHERE status='approved'")->fetch_row()[0];

// Fetch pending requests
$sql = "
SELECT sr.request_id, sr.user_id, sr.credential_filename, sr.credential_original_name, sr.status, sr.request_time, u.name, u.email, u.phone 
FROM seller_requests sr 
JOIN users u ON sr.user_id=u.user_id
WHERE sr.status='pending'
ORDER BY sr.request_time ASC";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SpareHub Admin – Seller Requests</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f6f8fb;
        }
        .container {
            display: flex;
        }
        .sidebar {
            background: #18181b;
            color: #fff;
            width: 220px;
            min-height: 100vh;
            padding: 28px 20px 12px 20px;
            box-sizing: border-box;
        }
        .sidebar .logo {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fb4a13;
            margin-bottom: 36px;
            letter-spacing:2px;
        }
        .sidebar nav a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 24px 0;
            padding: 9px 0 9px 14px;
            font-size: 1.13rem;
            border-radius:6px;
            transition: background 0.2s;
        }
        .sidebar nav a.active, .sidebar nav a:hover {
            background: #27272f;
            color: #fb4a13;
        }
        .sidebar-footer{
            position:absolute;
            bottom:24px;
            left:20px;
            right:20px;
        }
        .logout-btn{width:100%;padding:11px 0;background:#fb4a13;border:none;border-radius:6px;color:#fff;font-weight:600;font-size:1.08rem;cursor:pointer;}
        .main-content {
            flex: 1;
            padding: 40px 36px;
        }
        .dashboard-cards {
            display: flex;
            gap: 34px;
            margin-bottom: 44px;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 8px rgba(65,47,91,.03);
            padding: 26px 44px;
            min-width: 190px;
            text-align: left;
        }
        .dashboard-card .subtitle{
            font-size:.98em;color:#7d8590;margin-bottom:8px;
        }
        .dashboard-card .stat{
            font-size:2em;font-weight:700;margin-bottom:2px;color:#18181b;
        }
        .dashboard-card .stat.big{font-size:2.3em;}
        .dashboard-card .unit{font-size:.98em;color:#64748b;}
        .req-table-container{background:#fff;padding:34px 30px;border-radius:12px;margin-top:18px;}
        .request-table {
            width: 100%;
            border-collapse: collapse;
        }
        .request-table th, .request-table td {
            padding: 14px 10px;
            border-bottom: 1.5px solid #edf1f6;
            text-align: left;
        }
        .request-table th {
            background: #f8fafc;
            font-weight:700;font-size:1.07em;color:#64698D;
        }
        .request-table tbody tr:hover{background:#f3f4f8;}
        .cred-link { color: #2563eb; text-decoration: underline; font-weight: 500;}
        .btn-group { display: flex; gap: 8px; }
        .btn-approve { background: #10b981; color: #fff; padding: 8px 18px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-deny { background: #ef4444; color: #fff; padding: 8px 18px; border: none; border-radius: 4px; cursor: pointer; }
        .deny-reason-input { margin-top:6px;width:220px;border-radius:4px;padding:6px;border:1px solid #94a3b8;font-size:.97em;}
        .title {margin-bottom:12px; font-size:2rem; font-weight:700; letter-spacing:.5px;}
        .sub {margin-top:-16px;margin-bottom:34px;font-size:1.15em;color:#b2bbc6;}
    </style>
</head>
<body>
    <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">SpareHub</div>
        <nav>
            <a href="request.php" class="active">📝 Requests</a>
            <a href="admindashboard.php">🔧 Dashboard</a>
            <a href="analytics.php">📊 Analytics</a>
            <a href="logout.php">🚪 Logout</a>
        </nav>

    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="title">Seller Requests</div>
        <div class="sub">Review credentials for seller applications below</div>
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <div class="subtitle">Total Requests</div>
                <div class="stat big"><?= $totalRequests ?></div>
            </div>
            <div class="dashboard-card">
                <div class="subtitle">Pending</div>
                <div class="stat"><?= $pendingRequests ?></div>
            </div>
            <div class="dashboard-card">
                <div class="subtitle">Approved</div>
                <div class="stat"><?= $approvedRequests ?></div>
            </div>
        </div>
        <div class="req-table-container">
        <table class="request-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Credential File</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['request_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td>
                              <?php if ($row['credential_filename']): ?>
                                <a href="../pages/uploads/credentials/<?= urlencode($row['credential_filename']) ?>" target="_blank" class="cred-link">
                                <?= htmlspecialchars($row['credential_original_name']) ?>
                                </a>
                              <?php else: ?>
                                <span style="color: #f59e42;">No Uploaded File</span>
                              <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($row['request_time'])) ?></td>
                            <td>
                              <span style="color:#6366f1;padding:4px 12px;background:#e2e8fa;border-radius:6px;font-weight:500;"><?= ucfirst($row['status']) ?></span>
                            </td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="req_id" value="<?= $row['request_id'] ?>">
                                    <div class="btn-group">
                                        <button type="submit" name="approve" class="btn-approve"
                                            onclick="return confirm('Approve this user?');">Approve</button>
                                        <button type="button" class="btn-deny"
                                            onclick="document.getElementById('deny_<?= $row['request_id'] ?>').style.display='block';">Deny</button>
                                    </div>
                                    <div id="deny_<?= $row['request_id'] ?>" style="display:none;">
                                        <input type="text" name="denial_reason" class="deny-reason-input" placeholder="Denial reason (required)">
                                        <button type="submit" name="deny" class="btn-deny" style="margin-left:5px;">Confirm Deny</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center;color:#c7ac14;">No pending seller requests.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </main>
    </div>
</body>
</html>
