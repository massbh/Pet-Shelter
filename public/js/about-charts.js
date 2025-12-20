const chartColors = [
    '#FF6B00', '#FFA040', '#28A745', '#007bff', 
    '#dfa800', '#6c757d', '#17a2b8', '#e83e8c',
    '#fd7e14', '#20c997', '#6f42c1', '#dc3545'
];

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
                    backgroundColor: chartColors,
                    borderColor: chartColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: type === 'pie' || type === 'doughnut',
                        position: 'bottom'
                    },
                    title: {
                        display: false
                    }
                },
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

document.addEventListener('DOMContentLoaded', function() {
    createPublicChart('chartAvailableSpecies', 'available-pets-species', 'doughnut', 'Available Pets');
    createPublicChart('chartAvailableAge', 'available-pets-age', 'bar', 'Age Distribution');
    createPublicChart('chartAvailableGender', 'available-pets-gender', 'pie', 'Gender');
});
