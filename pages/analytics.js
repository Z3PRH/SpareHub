document.addEventListener('DOMContentLoaded', function () {
    // Monthly Sales (Line Chart)
    const salesChart = new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: ['May', 'June', 'July', 'Aug', 'Sep', 'Oct'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [100000, 105000, 110000, 115000, 120000, 125000],
                borderColor: '#2b6cb0',
                backgroundColor: 'rgba(43, 108, 176, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (₹)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });

    // Revenue by Product Category (Pie Chart)
    const categoryChart = new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: ['Brake Parts', 'Engine Parts', 'Filters', 'Clutch Parts'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: ['#2b6cb0', '#68d391', '#ed8936', '#f56565'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Inventory Status (Doughnut Chart)
    const inventoryChart = new Chart(document.getElementById('inventoryChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [180, 3, 42],
                backgroundColor: ['#68d391', '#ed8936', '#f56565'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Order Fulfillment Time (Bar Chart)
    const fulfillmentChart = new Chart(document.getElementById('fulfillmentChart'), {
        type: 'bar',
        data: {
            labels: ['Same Day', '1-2 Days', '3-5 Days', '>5 Days'],
            datasets: [{
                label: 'Orders',
                data: [100, 150, 80, 12],
                backgroundColor: '#2b6cb0',
                borderColor: '#1a4971',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Orders'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Fulfillment Time'
                    }
                }
            }
        }
    });
});