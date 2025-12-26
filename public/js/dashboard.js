// Sidebar Toggle Functionality
const sidebar = document.getElementById('sidebar');
// const sidebarToggle = document.getElementById('sidebarToggle'); // Old toggle
const desktopSidebarToggle = document.getElementById('desktopSidebarToggle');
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const body = document.body;

// Load sidebar state from localStorage
const sidebarState = localStorage.getItem('sidebarCollapsed');
if (sidebarState === 'true') {
    sidebar.classList.add('collapsed');
    body.classList.add('sidebar-collapsed');
}

// Desktop sidebar toggle
if (desktopSidebarToggle) {
    desktopSidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        body.classList.toggle('sidebar-collapsed'); // Add class to body for button rotation

        // Save state
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });
}

// Mobile menu toggle
if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
    });
}

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
        }
    }
});

// Chart Initialization (only on dashboard where canvas exists)
const chartCanvas = document.getElementById('ordersChart');
let ordersChart = null;

if (chartCanvas && typeof Chart !== 'undefined') {
    const ctx = chartCanvas.getContext('2d');

    // Sample data for the chart
    const chartData = {
        week: {
            labels: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
            data: [45, 78, 52, 90, 65, 38, 42]
        },
        month: {
            labels: ['1 Ara', '5 Ara', '10 Ara', '15 Ara', '20 Ara', '25 Ara', '30 Ara'],
            data: [52, 85, 63, 95, 78, 45, 88]
        },
        year: {
            labels: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'],
            data: [420, 380, 510, 490, 560, 530, 480, 520, 580, 610, 590, 640]
        }
    };

    // Initial chart configuration
    let currentPeriod = 'month';
    ordersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData[currentPeriod].labels,
            datasets: [{
                label: 'Siparişler',
                data: chartData[currentPeriod].data,
                backgroundColor: 'rgba(255, 87, 34, 0.8)',
                borderColor: 'rgba(255, 87, 34, 1)',
                borderWidth: 0,
                borderRadius: 8,
                barPercentage: 0.7,
                categoryPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(45, 55, 72, 0.95)',
                    padding: 12,
                    borderRadius: 8,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function (context) {
                            return 'Sipariş: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Inter'
                        },
                        color: '#718096'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Inter'
                        },
                        color: '#718096',
                        callback: function (value) {
                            return value;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 750,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Date selector change handler
    const dateSelector = document.getElementById('dateSelector');
    if (dateSelector) {
        dateSelector.addEventListener('change', (e) => {
            currentPeriod = e.target.value;

            // Update chart data with animation
            ordersChart.data.labels = chartData[currentPeriod].labels;
            ordersChart.data.datasets[0].data = chartData[currentPeriod].data;
            ordersChart.update('active');
        });
    }
}

// Handle window resize
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
        }
    }, 250);
});

// Add smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Active nav item handling
const navItems = document.querySelectorAll('.nav-item');
navItems.forEach(item => {
    item.addEventListener('click', function (e) {
        // Don't prevent default if it's a real link
        if (this.getAttribute('href') === '#') {
            e.preventDefault();
        }

        // Remove active class from all items
        navItems.forEach(nav => nav.classList.remove('active'));

        // Add active class to clicked item
        this.classList.add('active');

        // Close mobile menu on selection
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('mobile-open');
        }
    });
});

console.log('Dashboard initialized successfully');

