// partanalytics.js - Parts Analytics Chart Handler

let salesChart;

// Wait for DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
  
  // Check if elements exist before attaching listeners
  const toggleBtn = document.getElementById('toggleAnalytics');
  const analyticsSection = document.getElementById('analyticsSection');
  const stockBtn = document.getElementById('stockBtn');
  const salesBtn = document.getElementById('salesBtn');

  if (!toggleBtn || !analyticsSection) {
    console.error('Analytics elements not found');
    return;
  }

  // Toggle Analytics Section on button click
  toggleBtn.addEventListener('click', function() {
    if (analyticsSection.style.display === 'none' || analyticsSection.style.display === '') {
      analyticsSection.style.display = 'block';
      this.textContent = '📊 Hide Analytics';
      this.style.background = '#dc2626';
      loadStockChart();
    } else {
      analyticsSection.style.display = 'none';
      this.textContent = '📊 Show Analytics';
      this.style.background = '#2563eb';
    }
  });

  // Toggle between Stock and Sales charts
  if (stockBtn) {
    stockBtn.addEventListener('click', function() {
      setActiveButton('stockBtn');
      loadStockChart();
    });
  }

  if (salesBtn) {
    salesBtn.addEventListener('click', function() {
      setActiveButton('salesBtn');
      loadSalesChart();
    });
  }

});

// Set active button styling
function setActiveButton(btnId) {
  document.querySelectorAll('.chart-toggle-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  document.getElementById(btnId).classList.add('active');
}

// Load Stock Chart - Shows current stock for each part
function loadStockChart() {
  const ctx = document.getElementById('salesChart');
  if (!ctx) {
    console.error('Canvas element not found');
    return;
  }
  
  const context = ctx.getContext('2d');
  
  // Get parts data from PHP (embedded in the page)
  const partsData = window.partsChartData || [];
  
  if (partsData.length === 0) {
    context.clearRect(0, 0, ctx.width, ctx.height);
    context.font = '16px Inter';
    context.fillStyle = '#64748b';
    context.textAlign = 'center';
    context.fillText('No parts data available', ctx.width / 2, ctx.height / 2);
    return;
  }
  
  const partNames = partsData.map(p => p.name);
  const partStocks = partsData.map(p => p.stock);
  
  // Create color array - red for low stock, green for good stock
  const colors = partStocks.map(stock => stock < 5 ? '#ef4444' : '#10b981');
  
  destroyChart();
  
  salesChart = new Chart(context, {
    type: 'bar',
    data: {
      labels: partNames,
      datasets: [{
        label: 'Current Stock',
        data: partStocks,
        backgroundColor: colors,
        borderColor: colors.map(c => c === '#ef4444' ? '#dc2626' : '#059669'),
        borderWidth: 2,
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      indexAxis: 'y', // Horizontal bar chart
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            font: {
              size: 14,
              weight: 'bold'
            },
            padding: 15
          }
        },
        title: {
          display: true,
          text: 'Parts Inventory - Stock Levels',
          font: {
            size: 18,
            weight: 'bold'
          },
          padding: {
            top: 10,
            bottom: 20
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          callbacks: {
            label: function(context) {
              return 'Stock: ' + context.parsed.x + ' units';
            }
          }
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            stepSize: 5,
            font: {
              size: 12
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          }
        },
        y: {
          ticks: {
            font: {
              size: 12
            }
          },
          grid: {
            display: false
          }
        }
      }
    }
  });
}

// Load Sales Chart - Shows price for each part
function loadSalesChart() {
  const ctx = document.getElementById('salesChart');
  if (!ctx) {
    console.error('Canvas element not found');
    return;
  }
  
  const context = ctx.getContext('2d');
  
  // Get parts data from PHP (embedded in the page)
  const partsData = window.partsChartData || [];
  
  if (partsData.length === 0) {
    return;
  }
  
  const partNames = partsData.map(p => p.name);
  const partPrices = partsData.map(p => p.price);
  
  destroyChart();
  
  salesChart = new Chart(context, {
    type: 'bar',
    data: {
      labels: partNames,
      datasets: [{
        label: 'Price (₹)',
        data: partPrices,
        backgroundColor: '#ff6b35',
        borderColor: '#d64a19',
        borderWidth: 2,
        borderRadius: 8,
        hoverBackgroundColor: '#d64a19'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            font: {
              size: 14,
              weight: 'bold'
            },
            padding: 15
          }
        },
        title: {
          display: true,
          text: 'Parts Pricing Overview',
          font: {
            size: 18,
            weight: 'bold'
          },
          padding: {
            top: 10,
            bottom: 20
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          callbacks: {
            label: function(context) {
              return 'Price: ₹' + context.parsed.y.toFixed(2);
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '₹' + value;
            },
            font: {
              size: 12
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          }
        },
        x: {
          ticks: {
            font: {
              size: 12
            }
          },
          grid: {
            display: false
          }
        }
      }
    }
  });
}

// Destroy existing chart before creating a new one
function destroyChart() {
  if (salesChart) {
    salesChart.destroy();
  }
}
