const colors = [
        '#FF6B00', '#FFA040', '#28A745', '#007bff', 
        '#dfa800', '#6c757d', '#17a2b8', '#e83e8c',
        '#fd7e14', '#20c997', '#6f42c1', '#dc3545',
        '#ffc107', '#198754', '#0dcaf0', '#d63384',
        '#795548', '#ff5722', '#9c27b0', '#3f51b5'
    ];

let customChartInstance = null;
let currentCustomChart = null;
let currentChartConfig = null;
let isLoadingSavedCharts = false;

// Creates and renders a chart from API endpoint data
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
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        // Only show legend for circular charts
                        display: type === 'pie' || type === 'doughnut',
                        position: 'bottom',
                        reverse: true
                    },
                    title: {
                        display: false
                    }
                },
                // Configure axes only for bar and line charts
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

// Initializes all default analytics charts using parallel loading for performance
async function initializeCharts() {
    const loadingElement = document.getElementById('analyticsLoading');
    const chartsGrid = document.getElementById('analyticsChartsGrid');
    
    try {
        loadingElement.style.display = 'flex';
        chartsGrid.style.display = 'none';
        
        // Promise.all ensures all charts load simultaneously rather than sequentially
        await Promise.all([
            createChart('chartSpecies', 'pets-species', 'pie', 'Pets by Species'),
            createChart('chartMostRequestedSpecies', 'most-requested-species', 'doughnut', 'Most Requested Species'),
            createChart('chartOldestPets', 'oldest-pets', 'bar', 'Longest Time in Shelter'),
            createChart('chartMonthly', 'adoption-requests-month', 'line', 'Monthly Requests')
        ]);
        
        loadingElement.style.display = 'none';
        chartsGrid.style.display = 'grid';
    } catch (error) {
        console.error('Error initializing analytics charts:', error);
        loadingElement.innerHTML = '<p style="text-align: center; color: #C92A2A;">Error loading analytics dashboard. Please refresh the page.</p>';
    }
}

// Generates a custom chart based on user-selected type and data source
function generateCustomChart() {
    const chartType = document.getElementById('chartType').value;
    const dataSource = document.getElementById('dataSource').value;
    
    // Store configuration for potential save operation
    currentChartConfig = {
        chart_type: chartType,
        data_source: dataSource
    };
    
    document.getElementById('customChartArea').style.display = 'block';
    document.querySelector('.btn-save').style.display = 'inline-flex';
    
    fetchChartData(dataSource).then(data => {
        createCustomChart(chartType, data, dataSource);
    });
}

// Persists current custom chart configuration to database
function saveCurrentChart() {
    if (!currentChartConfig) {
        alert('Please generate a chart first');
        return;
    }
    
    // Generate human-readable default names
    const chartTypeNames = {
        'bar': 'Bar',
        'pie': 'Pie',
        'doughnut': 'Doughnut',
        'line': 'Line'
    };
    
    const defaultName = `${chartTypeNames[currentChartConfig.chart_type]} - ${getTitleFromDataSource(currentChartConfig.data_source)}`;
    
    const title = prompt('Enter a name for this chart:', defaultName);
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
            config: null
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

// Loads and renders all user-saved charts with parallel data fetching
async function loadSavedCharts() {
    // Prevent race conditions from multiple simultaneous calls
    if (isLoadingSavedCharts) {
        return;
    }
    
    isLoadingSavedCharts = true;
    
    const loadingElement = document.getElementById('savedChartsLoading');
    const gridElement = document.getElementById('savedChartsGrid');
    loadingElement.style.display = 'flex';
    gridElement.innerHTML = '';
    
    try {
        const response = await fetch('/api/charts/saved');
        const charts = await response.json();
        
        if (charts.length === 0) {
            loadingElement.style.display = 'none';
            document.getElementById('savedChartsSection').style.display = 'none';
            return;
        }
        
        document.getElementById('savedChartsSection').style.display = 'block';
        
        // Fetch all chart data concurrently to improve load time
        const chartPromises = charts.map(chart => 
            fetchChartData(chart.data_source).then(data => ({ chart, data }))
        );
        
        const chartDataArray = await Promise.all(chartPromises);
        
        // Render charts sequentially to avoid DOM conflicts
        for (const { chart, data } of chartDataArray) {
            const card = createSavedChartCard(chart, data);
            gridElement.appendChild(card);
            
            // Ensure DOM update before chart initialization
            await new Promise(resolve => requestAnimationFrame(resolve));
            
            const canvas = document.getElementById(`savedChart${chart.id}`);
            if (canvas && canvas.getContext && !canvas.dataset.chartInitialized) {
                try {
                    canvas.dataset.chartInitialized = 'true';
                    createCustomChart(chart.chart_type, data, chart.data_source, canvas);
                } catch (error) {
                    console.error(`Error rendering chart ${chart.id}:`, error);
                    canvas.dataset.chartInitialized = 'false';
                }
            }
        }
        
        loadingElement.style.display = 'none';
        lucide.createIcons();
    } catch (error) {
        console.error('Error loading saved charts:', error);
        loadingElement.style.display = 'none';
        gridElement.innerHTML = '<p style="text-align: center; color: #C92A2A;">Error loading saved charts. Please try again.</p>';
    } finally {
        isLoadingSavedCharts = false;
    }
}

function createSavedChartCard(chart, data) {
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

// Instantiates a Chart.js object with provided configuration
function createCustomChart(type, data, dataSource, canvas = null) {
    const ctx = canvas || document.getElementById('customChart');
    
    // Clean up existing chart instance to prevent memory leaks
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
                    reverse: true
                }
            }
        }
    });
    
    if (!canvas) {
        currentCustomChart = chart;
        document.getElementById('customChartTitle').textContent = getTitleFromDataSource(dataSource);
    }
}

// Maps data source identifiers to human-readable chart titles
function getTitleFromDataSource(dataSource) {
    const titles = {
        'pets-species': 'Pets by Species',
        'pets-status': 'Pets by Status',
        'pets-age': 'Pets by Age',
        'pets-gender': 'Pets by Gender',
        'adoption-requests-status': 'Adoption Requests by Status',
        'adoption-requests-month': 'Adoption Requests per Month',
        'most-requested-pets': 'Most Requested Pets',
        'average-age-by-species': 'Average Age by Species',
        'gender-distribution-by-species': 'Gender Distribution by Species',
        'newest-pets': 'Shortest Time in Shelter',
        'oldest-pets': 'Longest Time in Shelter',
        'pets-created-by-month': 'Pets Added Over Time',
        'requests-by-user': 'Requests by User',
        'most-requested-species': 'Most Requested Species',
        'most-requested-age-groups': 'Most Requested Age Groups',
        'user-registrations-over-time': 'User Registrations Over Time',
        'seasonal-trends': 'Seasonal Adoption Trends'
    };
    return titles[dataSource] || 'Custom Chart';
}

// Fetches and formats chart data from API endpoint
async function fetchChartData(dataSource) {
    try {
        const response = await fetch(`/api/charts/${dataSource}`);
        const data = await response.json();
        
        // Handle grouped datasets (e.g., gender distribution by species)
        if (data.datasets && Array.isArray(data.datasets)) {
            return {
                labels: data.labels,
                datasets: data.datasets.map((dataset, index) => ({
                    label: dataset.label,
                    data: dataset.data,
                    backgroundColor: index === 0 ? colors : colors.map(c => c + '99'),
                    borderColor: colors,
                    borderWidth: 1
                }))
            };
        }
        
        // Standard single-dataset format
        return {
            labels: data.labels,
            datasets: [{
                label: getTitleFromDataSource(dataSource),
                data: data.values,
                backgroundColor: colors,
                borderColor: colors,
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

// Initialize analytics dashboard on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadSavedCharts();
});