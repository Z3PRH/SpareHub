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
    
    <meta charset="UTF-8">
    <title>SpareHub</title>
    
    <link rel="stylesheet" href="../styles/partdetail.css ">
    <style type="text/css">
        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 300;
            src: url(/cf-fonts/s/poppins/5.0.11/latin/300/normal.woff2);
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 300;
            src: url(/cf-fonts/s/poppins/5.0.11/latin-ext/300/normal.woff2);
            unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 300;
            src: url(/cf-fonts/s/poppins/5.0.11/devanagari/300/normal.woff2);
            unicode-range: U+0900-097F, U+1CD0-1CF9, U+200C-200D, U+20A8, U+20B9, U+25CC, U+A830-A839, U+A8E0-A8FF;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 500;
            src: url(/cf-fonts/s/poppins/5.0.11/devanagari/500/normal.woff2);
            unicode-range: U+0900-097F, U+1CD0-1CF9, U+200C-200D, U+20A8, U+20B9, U+25CC, U+A830-A839, U+A8E0-A8FF;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 500;
            src: url(/cf-fonts/s/poppins/5.0.11/latin-ext/500/normal.woff2);
            unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 500;
            src: url(/cf-fonts/s/poppins/5.0.11/latin/500/normal.woff2);
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 700;
            src: url(/cf-fonts/s/poppins/5.0.11/devanagari/700/normal.woff2);
            unicode-range: U+0900-097F, U+1CD0-1CF9, U+200C-200D, U+20A8, U+20B9, U+25CC, U+A830-A839, U+A8E0-A8FF;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 700;
            src: url(/cf-fonts/s/poppins/5.0.11/latin-ext/700/normal.woff2);
            unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
            font-display: swap;
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 700;
            src: url(/cf-fonts/s/poppins/5.0.11/latin/700/normal.woff2);
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            font-display: swap;
        }
    </style>
</head>


<body>
    <div class="navbar">
        <div class="brand">SpareHub</div>
        <div class="links">
            <ul>
                <li><a href="./Homepage.php">Home</a></li>
                <?php if ($showLogin): ?>
                    <li><a href="./Login.html" class="sign">LogIn</a></li>
                <?php endif; ?>
                <li>
                    <a href="./cart.php" class="cart-icon-link" title="View Cart">
                        <span class="cart-icon">
                            🛒
                            <span id="cart-count" class="cart-count">0</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <div class="part-details-container">
        <a href="./results.php" class="back-link">← Back to Search</a>
        <h1 id="part-name">Part Name</h1>
        <div class="part-info">
            <img id="part-image" src="" alt="Part Image">
            <p>Price: <span id="part-price"></span></p>
            <p>Brand: <span id="part-brand"></span></p>
            <p>Model: <span id="part-model"></span></p>
            <p>Year: <span id="part-year"></span></p>
            <p>Condition: <span id="part-condition"></span></p>
            <p>Availability: <span id="part-availability"></span></p>
            <form action="buyerhandler.php" method="post" style="display:inline;">
                <input type="hidden" name="part_id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>" />
                <button type="submit" class="buy-now-btn">Buy Now</button>
                
            </form>
            <a href="#" class="add-to-cart-btn">Add to Cart</a>
            <p>Description: <span id="part-description"></span></p>
            </div>
        <div class="rating-section">
            <h2>Customer Rating</h2>
            <p id="part-rating">★★★★☆ (4.2/5 based on 56 reviews)</p>
            </div>
        <div class="reviews-section">
            <h2>Customer Reviews</h2>
            <div id="part-reviews">
                <div>
                    <p>John D. <span class="review-rating">★★★★★</span></p>
                    <p class="review-text">Great part, works perfectly in my car. Fast shipping too!
                    </p>
                    <p class="review-date">Posted on August 10, 2025</p>
                    </div>
                <div>
                    <p>Sarah M. <span class="review-rating">★★★★☆</span></p>
                    <p class="review-text">Good quality, but installation was a bit tricky.</p>
                    <p class="review-date">Posted on August 5, 2025</p>
                    </div>
                </div>
            </div>
        <div class="review-form-section">
            <h2>Write a Review</h2>
            <form onsubmit="submitReview(event);">
                <label for="rating">Rating:</label>
                <select id="rating" name="rating">
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="1">★☆☆☆☆</option>
                    </select>
                <label for="review-text">Your Review:</label>
                <textarea id="review-text" name="review-text"
                    placeholder="Write your review here..."></textarea>
                <button type="submit">Submit Review</button>
                </form>
            </div>
        <div class="related-parts-section">
            <h2>Related Parts</h2>
            <ul>
                <li>
                    <a href="partdetails.html?id=2" style="text-decoration:none; color:inherit;">
                        <div class="related-part-image">
                            <img src="../images/brakepad.png" alt="Related Part 1">
                            </div>
                        <span class="related-part-name">Brake Pad Set</span>
                        <div class="related-part-info">
                            <span class="related-part-condition">Condition: New</span>
                            <span class="related-part-stock available">In Stock</span>
                            </div>
                        </a>
                    </li>
                <li>
                    <a href="partdetails.html?id=3" style="text-decoration:none; color:inherit;">
                        <div class="related-part-image">
                            <img src="../images/clutchplate.png" alt="Related Part 2">
                            </div>
                        <span class="related-part-name">Clutchplate</span>
                        <div class="related-part-info">
                            <span class="related-part-condition">Condition:
                                Refurbished</span><br>
                            <span class="related-part-stock unavailable">In Stock</span>
                            </div>
                        </a>
                    </li>
                <li>
                    <a href="partdetails.html?id=5" style="text-decoration:none; color:inherit;">
                        <div class="related-part-image">
                            <img src="../images/headlight.png" alt="Related Part 3">
                            </div>
                        <span class="related-part-name">Headlight</span>
                        <div class="related-part-info">
                            <span class="related-part-condition">Condition:
                                Refurbished</span>
                            <span class="related-part-stock available">In Stock</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        <footer>© 2025 AutoParts. All rights reserved.</footer>
        </div>
    
    <script src="partdetails.js"></script>
    
    <script>
        document.querySelector('.add-to-cart-btn').addEventListener('click', function (e) {
            e.preventDefault();
            const part = {
                id: new URLSearchParams(window.location.search).get('id') || '1',
                name: document.getElementById('part-name').textContent,
                price: document.getElementById('part-price').textContent,
                image: document.getElementById('part-image').src,
                quantity: 1
            };
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existing = cart.find(item => item.id === part.id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push(part);
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            alert('Added to cart!');
        });
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            var el = document.getElementById('cart-count');
            if (el) el.textContent = count;
        }
        updateCartCount();
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id') || 1;
            fetch(`getpart.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('part-name').textContent = 'Part Not Found';
                        return;
                    }
                    document.getElementById('part-name').textContent = data.name;
                    document.getElementById('part-image').src = data.image || '../images/default.png';
                    document.getElementById('part-price').textContent = '₹' + data.price;
                    document.getElementById('part-brand').textContent = data.brand;
                    document.getElementById('part-model').textContent = data.model;
                    document.getElementById('part-year').textContent = data.year;
                    document.getElementById('part-condition').textContent = data.condition;
                    document.getElementById('part-availability').textContent = (data.stock >= 50) ? 'In Stock' : 'Out of Stock';
                    document.getElementById('part-description').textContent = data.description;
                })
                .catch(err => {
                    document.getElementById('part-name').textContent = 'Error loading part details';
                });
        });
    </script>
</body>


</html>