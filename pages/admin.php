<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: Login.html?error=Unauthorized access");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SpareHub</title>
    <link rel="stylesheet" href="../styles/about.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="brand">SpareHub</div>

    </div>
    <div class="admin-main">
        <div class="admin-content">
            <h2>Welcome, Admin!</h2>
            <p>Manage your SpareHub inventory below.</p>
            <h3>Spare Parts Inventory</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Part Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Brake Pad Set</td>
                        <td>₹800</td>
                        <td>50</td>
                        <td>
                            <button class="action-btn edit-btn" onclick="editProduct(event, this)">Edit</button>
                            <button class="action-btn delete-btn" onclick="deleteProduct(event, this)">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Clutchplate</td>
                        <td>₹2000</td>
                        <td>100</td>
                        <td>
                            <button class="action-btn edit-btn" onclick="editProduct(event, this)">Edit</button>
                             <button class="action-btn delete-btn" onclick="deleteProduct(event, this)">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Air Filter</td>
                        <td>₹500</td>
                        <td>75</td>
                        <td>
                            <button class="action-btn edit-btn" onclick="editProduct(event, this)">Edit</button>
                            <button class="action-btn delete-btn" onclick="deleteProduct(event, this)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <a href="logout.php" class="logout-btn">Logout</a>
            <button class="action-btn" onclick="location.href='admin2.php'">View Analytics</button>
        </div>
    </div>
    <script>
        function editProduct(event, element) {
            // Edit product logic
            const row = element.closest('tr');
            const partName = row.children[0].innerText;
            const price = row.children[1].innerText;
            const stock = row.children[2].innerText;
            
            // Populate the form fields in the modal
            document.getElementById('editPartName').value = partName;
            document.getElementById('editPrice').value = price.replace('₹', '');
            document.getElementById('editStock').value = stock;
            
            // Show the modal
            document.getElementById('editModal').style.display = 'block';
        }

        function deleteProduct(event, element) {
            // Delete product logic
            const row = element.closest('tr');
            const partName = row.children[0].innerText;
            
            // Confirm deletion
            if (confirm('Are you sure you want to delete ' + partName + '?')) {
                // Remove the row from the table
                row.parentNode.removeChild(row);
            }
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            <h2>Edit Product</h2>
            <label for="editPartName">Part Name:</label>
            <input type="text" id="editPartName" required>
            <label for="editPrice">Price:</label>
            <input type="number" id="editP
            
            
            
            +rice" required>
            <label for="editStock">Stock:</label>
            <input type="number" id="editStock" required>
            <button class="action-btn" onclick="updateProduct()">Update</button>
        </div>
    </div>

    <script>
        function updateProduct() {
            // Update product logic
            const partName = document.getElementById('editPartName').value;
            const price = document.getElementById('editPrice').value;
            const stock = document.getElementById('editStock').value;
            
            // Find the row in the table and update its content
            const rows = document.querySelectorAll('.inventory-table tbody tr');
            rows.forEach(row => {
                if (row.children[0].innerText === partName) {
                    row.children[1].innerText = '₹' + price;
                    row.children[2].innerText = stock;
                }
            });
            
            // Close the modal
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>