// Color schemes
const colors = {
    primary: ['#FF6B00', '#FFA040', '#FFD580', '#AC5512', '#8a4510'],
    status: {
        available: '#28A745',
        pending: '#dfa800',
        adopted: '#6c757d'
    },
    rainbow: [
        '#FF6B00', '#FFA040', '#28A745', '#007bff', 
        '#dfa800', '#6c757d', '#17a2b8', '#e83e8c'
    ]
};

let customChartInstance = null;
let currentCustomChart = null;
let currentChartConfig = null;

// Utility function to create charts
async function createChart(canvasId, endpoint, type, title) {
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
                    backgroundColor: colors.rainbow,
                    borderColor: colors.rainbow,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: type === 'pie' || type === 'doughnut',
                        position: 'bottom'
                    },
                    title: {
                        display: false
                    }
                },
                scales: type === 'bar' || type === 'line' ? {
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

// Initialize all default charts
async function initializeCharts() {
    await createChart('chartSpecies', 'pets-species', 'doughnut', 'Pets by Species');
    await createChart('chartStatus', 'pets-status', 'pie', 'Pets by Status');
    await createChart('chartAdoptionStatus', 'adoption-requests-status', 'doughnut', 'Adoption Requests');
    await createChart('chartAge', 'pets-age', 'bar', 'Age Distribution');
    await createChart('chartMonthly', 'adoption-requests-month', 'line', 'Monthly Requests');
    await createChart('chartMostRequested', 'most-requested-pets', 'bar', 'Most Requested');
}

// Generate custom chart
function generateCustomChart() {
    const chartType = document.getElementById('chartType').value;
    const dataSource = document.getElementById('dataSource').value;
    
    // Store current configuration
    currentChartConfig = {
        chart_type: chartType,
        data_source: dataSource
    };
    
    // Show custom chart area and save button
    document.getElementById('customChartArea').style.display = 'block';
    document.querySelector('.btn-save').style.display = 'inline-flex';
    
    // Fetch data and create chart
    fetchChartData(dataSource).then(data => {
        createCustomChart(chartType, data, dataSource);
    });
}

function saveCurrentChart() {
    if (!currentChartConfig) {
        alert('Please generate a chart first');
        return;
    }
    
    const title = prompt('Enter a name for this chart:');
    if (!title) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch('/charts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            title: title,
            chart_type: currentChartConfig.chart_type,
            data_source: currentChartConfig.data_source,
            config: currentChartConfig
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Chart saved successfully!');
            loadSavedCharts();
        }
    })
    .catch(error => {
        console.error('Error saving chart:', error);
        alert('Failed to save chart');
    });
}

function loadSavedCharts() {
    fetch('/api/charts/saved')
        .then(response => response.json())
        .then(charts => {
            const grid = document.getElementById('savedChartsGrid');
            grid.innerHTML = '';
            
            if (charts.length === 0) {
                document.getElementById('savedChartsSection').style.display = 'none';
                return;
            }
            
            document.getElementById('savedChartsSection').style.display = 'block';
            
            charts.forEach(chart => {
                const card = createSavedChartCard(chart);
                grid.appendChild(card);
            });
            
            lucide.createIcons();
        })
        .catch(error => console.error('Error loading saved charts:', error));
}

function createSavedChartCard(chart) {
    const card = document.createElement('div');
    card.className = 'chart-card';
    card.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>${chart.title}</h3>
            <button class="delete-chart-btn" onclick="deleteChart(${chart.id})" title="Delete chart">
                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
            </button>
        </div>
        <div class="chart-wrapper">
            <canvas id="savedChart${chart.id}"></canvas>
        </div>
    `;
    
    // Load and render the saved chart
    setTimeout(() => {
        fetchChartData(chart.data_source).then(data => {
            const canvas = document.getElementById(`savedChart${chart.id}`);
            createCustomChart(chart.chart_type, data, chart.data_source, canvas);
        });
    }, 100);
    
    return card;
}

function deleteChart(id) {
    if (!confirm('Are you sure you want to delete this chart?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    fetch(`/charts/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadSavedCharts();
        }
    })
    .catch(error => {
        console.error('Error deleting chart:', error);
        alert('Failed to delete chart');
    });
}

function createCustomChart(type, data, dataSource, canvas = null) {
    const ctx = canvas || document.getElementById('customChart');
    
    if (currentCustomChart && !canvas) {
        currentCustomChart.destroy();
    }
    
    const chart = new Chart(ctx, {
        type: type,
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
    
    if (!canvas) {
        currentCustomChart = chart;
        document.getElementById('customChartTitle').textContent = getTitleFromDataSource(dataSource);
    }
}

function getTitleFromDataSource(dataSource) {
    const titles = {
        'pets-species': 'Pets by Species',
        'pets-status': 'Pets by Status',
        'pets-age': 'Pets by Age',
        'pets-gender': 'Pets by Gender',
        'adoption-requests-status': 'Adoption Requests by Status',
        'adoption-requests-month': 'Adoption Requests per Month',
        'most-requested-pets': 'Most Requested Pets'
    };
    return titles[dataSource] || 'Custom Chart';
}

async function fetchChartData(dataSource) {
    try {
        const response = await fetch(`/api/charts/${dataSource}`);
        const data = await response.json();
        
        return {
            labels: data.labels,
            datasets: [{
                label: getTitleFromDataSource(dataSource),
                data: data.values,
                backgroundColor: colors.rainbow,
                borderColor: colors.rainbow,
                borderWidth: 1
            }]
        };
    } catch (error) {
        console.error(`Error fetching chart data for ${dataSource}:`, error);
        return {
            labels: ['No Data'],
            datasets: [{
                label: 'Error',
                data: [0],
                backgroundColor: ['#6c757d']
            }]
        };
    }
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadSavedCharts();
});