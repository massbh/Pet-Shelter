<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Happinest - Chart Builder</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/chartbuilder.js') }}" defer></script>
</head>
<body>
    <main>
        <section class="hero">
            <div class="hero-inner">
                <h1>Chart Builder</h1>
                <p>Create custom visualizations of your shelter data and view analytics dashboard</p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('dashboard') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; padding: 12px 24px;">
                        <i data-lucide="arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </section>

        <div class="chart-builder-container">
            <!-- Chart Builder Controls -->
            <div class="builder-controls">
                <h2>Create Custom Chart</h2>
                
                <div class="control-group">
                    <label for="chartType">Chart Type</label>
                    <select id="chartType">
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="doughnut">Doughnut Chart</option>
                        <option value="line">Line Chart</option>
                    </select>
                </div>

                <div class="control-group">
                    <label for="dataSource">Data Source</label>
                    <select id="dataSource">
                        <optgroup label="Pet Statistics">
                            <option value="pets-species">Pets by Species</option>
                            <option value="pets-status">Pets by Status</option>
                            <option value="pets-age">Pets by Age Group</option>
                            <option value="pets-gender">Pets by Gender</option>
                            <option value="average-age-by-species">Average Age by Species</option>
                            <option value="gender-distribution-by-species">Gender Distribution by Species</option>
                            <option value="newest-pets">Shortest Time in Shelter</option>
                            <option value="oldest-pets">Longest Time in Shelter</option>
                            <option value="pets-created-by-month">Pets Added Over Time</option>
                        </optgroup>
                        <optgroup label="Adoption Requests">
                            <option value="adoption-requests-status">Adoption Requests by Status</option>
                            <option value="adoption-requests-month">Adoption Requests per Month</option>
                            <option value="most-requested-pets">Most Requested Pets</option>
                            <option value="most-requested-species">Most Requested Species</option>
                            <option value="most-requested-age-groups">Most Requested Age Groups</option>
                            <option value="requests-by-user">Requests by User</option>
                            <option value="seasonal-trends">Seasonal Trends</option>
                        </optgroup>
                        <optgroup label="User Analytics">
                            <option value="user-registrations-over-time">User Registrations Over Time</option>
                        </optgroup>
                    </select>
                </div>

                <button class="btn-generate" onclick="generateCustomChart()">
                    <i data-lucide="plus-circle"></i> Generate Chart
                </button>

                <button class="btn-save" onclick="saveCurrentChart()" style="display: none; margin-left: 10px;">
                    <i data-lucide="save"></i> Save Chart
                </button>
            </div>
            
            <!-- Dynamic Chart Area -->
            <div id="customChartArea" style="display: none; margin-top: 2rem;">
                <h2 class="caption">Custom Chart Preview</h2>
                <div class="chart-card">
                    <h3 id="customChartTitle">Custom Chart</h3>
                    <div class="chart-wrapper" style="height: 400px;">
                        <canvas id="customChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Saved Charts Section -->
            <div id="savedChartsSection" style="margin-top: 2rem;">
                <h2 class="caption">Saved Charts</h2>
                <div id="savedChartsGrid" class="chart-grid"></div>
            </div>

            <!-- Pre-built Charts Grid -->
            <h2 class="caption" style="margin-top: 2rem;">Analytics Dashboard</h2>
            <div class="chart-grid">
                <div class="chart-card">
                    <h3>Pets by Species</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartSpecies"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>Most Requested Species</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartMostRequestedSpecies"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>Longest Time in Shelter</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartOldestPets"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>Adoption Requests (Last 12 Months)</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartMonthly"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>