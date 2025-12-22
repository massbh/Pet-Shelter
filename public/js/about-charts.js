// Color palette for consistent chart styling across public pages
const colors = [
        '#FF6B00', '#FFA040', '#28A745', '#007bff', 
        '#dfa800', '#6c757d', '#17a2b8', '#e83e8c',
        '#fd7e14', '#20c997', '#6f42c1', '#dc3545',
        '#ffc107', '#198754', '#0dcaf0', '#d63384',
        '#795548', '#ff5722', '#9c27b0', '#3f51b5'
    ];

// Creates and renders public-facing charts from API data
async function createPublicChart(canvasId, endpoint, type, title) {
    try {
        const response = await fetch(`/api/charts/${endpoint}`);
        const data = await response.json();

        const ctx = document.getElementById(canvasId).getContext('2d');
        
        new Chart(ctx, {
            type: type,
            data: {
                labels: data.labels,
                datasets: [{
                    label: title,
                    data: data.values,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        // Show legend only for circular chart types
                        display: type === 'pie' || type === 'doughnut',
                        position: 'bottom'
                    },
                    title: {
                        display: false
                    }
                },
                // Configure Y-axis only for bar charts
                scales: type === 'bar' ? {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                } : {}
            }
        });
    } catch (error) {
        console.error(`Error creating chart ${canvasId}:`, error);
    }
}

// Initialize public statistics charts on page load
document.addEventListener('DOMContentLoaded', function() {
    createPublicChart('chartAvailableSpecies', 'available-pets-species', 'doughnut', 'Available Pets');
    createPublicChart('chartAvailableAge', 'available-pets-age', 'bar', 'Age Distribution');
    createPublicChart('chartAvailableGender', 'available-pets-gender', 'pie', 'Gender');
});
