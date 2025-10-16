// Updated partdetails.js to fetch data from the server

// Function to handle fetching and displaying part details
async function fetchPartDetails() {
    // Get part ID from URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const partId = urlParams.get('id');

    if (!partId) {
        document.getElementById('part-name').textContent = "Part Not Found";
        console.error('Error: Part ID is missing from the URL.');
        return;
    }

    try {
        console.log(`Attempting to fetch part details for ID: ${partId}`);
        const response = await fetch(`../database/getpart.php?id=${partId}`);
        
        // Check if the response was successful (e.g., status 200)
        if (!response.ok) {
            console.error('Fetch failed:', response.status, response.statusText);
            document.getElementById('part-name').textContent = `Error: Server responded with status ${response.status}`;
            return;
        }

        const result = await response.json();
        console.log('API response received:', result);

        if (result.success) {
            const part = result.part;
            
            // Populate the page with data from the database
            document.getElementById('part-name').textContent = part.name;
            document.getElementById('part-image').src = part.images || 'https://placehold.co/400x300/e0e0e0/000000?text=Image+Not+Found';
            document.getElementById('part-price').textContent = `₹${part.price}`;
            document.getElementById('part-brand').textContent = part.brand;
            document.getElementById('part-model').textContent = part.model;
            document.getElementById('part-year').textContent = part.year;
            document.getElementById('part-condition').textContent = part.conditions;
            document.getElementById('part-availability').textContent = part.stock > 0 ? "In Stock" : "Out of Stock";
            document.getElementById('part-description').textContent = part.description;
        } else {
            document.getElementById('part-name').textContent = result.message;
            console.error('PHP script reported an error:', result.message);
        }
    } catch (error) {
        console.error('Error fetching part details:', error);
        document.getElementById('part-name').textContent = "Error loading part details. Check the browser console for details.";
    }
}

// Function to handle review submission
function submitReview(event) {
    event.preventDefault(); // Prevent the form from refreshing the page
    
    // Get the user's input from the form
    const rating = document.getElementById("rating").value;
    const reviewText = document.getElementById("review-text").value.trim();

    // Check if the review text is empty
    if (reviewText === "") {
        // Use a custom message box instead of alert()
        alert('Nothing to submit, Review Empty');
        return;
    }
    
    // Create a new review element
    const newReview = document.createElement('div');
    newReview.className = 'new-review-item'; // Add a class for potential styling
    
    // Get the star rating based on the selected value
    const starRating = "★★★★★☆☆☆☆☆".substring(5 - parseInt(rating), 10 - parseInt(rating));
    
    // Get the current date for the review
    const today = new Date();
    const reviewDate = today.toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });

    // Set the inner HTML of the new review element
    newReview.innerHTML = `
        <div>
            <p>You <span class="review-rating">${starRating}</span></p>
            <p class="review-text">${reviewText}</p>
            <p class="review-date">Posted on ${reviewDate}</p>
        </div>
    `;

    // Append the new review to the reviews container
    const reviewsContainer = document.getElementById('part-reviews');
    reviewsContainer.appendChild(newReview);
    
    // Clear the form after submission
    document.getElementById("review-text").value = "";
    document.getElementById("rating").value = "5"; // Reset rating to default
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', fetchPartDetails);

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    var el = document.getElementById('cart-count');
    if (el) el.textContent = count;
}
updateCartCount();
