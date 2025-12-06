<?php
session_start();
$showLogin = true;
$buyerName = '';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'buyer') {
    $showLogin = false;
    $buyerName = isset($_SESSION['buyername']) ? $_SESSION['buyername'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>SpareHub - Home</title>
    <link rel="stylesheet" href="../styles/mains.css">
</head>
<body>
    <div class="navbar">
        <span class="brand">SpareHub</span>
        <div class="nav-links">
            <a href="Homepage.php">Home</a>
            <a href="about.html">About</a>
            <?php if ($showLogin): ?>
                <a href="Login.html" class="login-btn">LogIn</a>
            <?php else: ?>
                <a href="buyerdashboard.php" class="profile-link">Hello, <?php echo htmlspecialchars($buyerName); ?></a>
                <a href="logout.php" class="logout-link">Logout</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="homepage-bg">
        <div class="homepage-main-card">
            <h2>Find Quality Spare Parts</h2>
            <form id="searchForm">
                <div class="selectors">
                    <select id="brand" name="brand">
                        <option value="">Select Brand</option>
                        <option value="Volkswagen">Volkswagen</option>
                        <option value="Honda">Honda</option>
                        <option value="Maruti">Maruti</option>
                    </select>
                    <select id="model" name="model">
                        <option value="">Select Model</option>
                        <option value="Model1">Model1</option>
                        <option value="Model2">Model2</option>
                    </select>
                    <select id="year" name="year">
                        <option value="">Select Year</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <div class="search-buttons">
                    <button type="button" onclick="searchParts()" name="searchButton1" class="search-btn">Search</button>
                    <button type="button" onclick="searchAllParts()" name="searchButton2" class="all-btn">Search All Parts</button>
                </div>
            </form>
            <p>
                Search for spare parts for brands like Volkswagen, Honda, and Maruti. All parts include OEM numbers for authenticity.
            </p>
        </div>
    </div>
    <script src="./script.js"></script>
</body>
</html>
