<?php
session_start();
if (isset($_SESSION['redirect_after_login'])) {
    $redirectUrl = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']);
} else {
    $redirectUrl = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpareHub - Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .payment-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .payment-option {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .payment-option:hover {
            background-color: #f9f9f9;
        }
        .payment-option img {
            width: 50px;
            height: 50px;
            margin-right: 20px;
            object-fit: contain;
        }
        .payment-option h3 {
            margin: 0;
            font-size: 1.2em;
            color: #555;
        }

        /* BACK LINK BUTTONS STYLES */
        .back-links {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
            align-items: center;
        }
        .back-links a {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1em;
            box-shadow: 0 2px 8px #007bff20;
            transition: background 0.2s, color 0.2s;
        }
        .back-links a.cart-link {
            color: #fff;
            background: #007bff;
        }
        .back-links a.cart-link:hover {
            background: #0056b3;
        }
        .back-links a.search-link {
            color: #fff;
            background: #6c63ff;
        }
        .back-links a.search-link:hover {
            background: #4239b9;
        }
        .back-arrow {
            font-size: 1.17em;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="back-links">
            <a href="cart.php" class="cart-link"><span class="back-arrow">&#8678;</span>Cart</a>
            <a href="results.php" class="search-link"><span class="back-arrow">&#8678;</span>Search Results</a>
        </div>

        <h1>Choose a Payment Method</h1>
        <div class="payment-options">
            <div class="payment-option" onclick="window.location.href='netbankpay.html'">
                <img src="../images/netbanking.png" alt="Netbanking Icon">
                <h3>Netbanking</h3>
            </div>
            <div class="payment-option">
                <img src="../images/upi.png" alt="UPI Icon" onclick="selectPayment('UPI')">
                <h3>UPI</h3>
            </div>
            <div class="payment-option" onclick="window.location.href='cod.html'">
                <img src="../images/cod.png" alt="Cash on Delivery Icon">
                <h3>Cash on Delivery</h3>
            </div>
        </div>
    </div>
    <script>
        function selectPayment(method) {
            alert(`You have selected ${method}. Proceeding to checkout...`);
            // Add further logic as desired
        }
    </script>
</body>
</html>
