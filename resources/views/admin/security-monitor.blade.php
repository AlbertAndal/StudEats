@extends('layouts.admin')

@section('title', 'Security Monitor')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600 lucide lucide-shield" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                        </svg>
                        Security Monitor
                    </h1>
                    <p class="mt-2 text-gray-600">Monitor CSRF errors, session health, and security events</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-green-700">Real-time Monitoring</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Last updated: <span id="last-updated">--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- CSRF Errors Today -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400 lucide lucide-alert-triangle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                <path d="M12 9v4"/>
                                <path d="m12 17 .01 0"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">CSRF Errors Today</dt>
                                <dd class="text-lg font-medium text-gray-900" id="csrf-errors-today">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400 lucide lucide-users" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="m22 21-2-2a4 4 0 0 0-4-4h-1"/>
                                <circle cx="16" cy="7" r="3"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Sessions</dt>
                                <dd class="text-lg font-medium text-gray-900" id="active-sessions">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Alerts -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400 lucide lucide-shield-alert" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                <path d="M12 8v4"/>
                                <path d="M12 16h.01"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Security Alerts</dt>
                                <dd class="text-lg font-medium text-gray-900" id="security-alerts">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session Health -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400 lucide lucide-shield-check" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                <path d="m9 12 2 2 4-4"/>
            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Session Health</dt>
                                <dd class="text-lg font-medium text-gray-900" id="session-health">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Detailed Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- CSRF Error Timeline -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">CSRF Errors Timeline</h3>
                    <div class="h-64">
                        <canvas id="csrf-timeline-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Session Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Session Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Total Sessions (24h)</span>
                            <span class="text-sm text-gray-900" id="total-sessions-24h">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Expired Sessions</span>
                            <span class="text-sm text-gray-900" id="expired-sessions">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Average Session Duration</span>
                            <span class="text-sm text-gray-900" id="avg-session-duration">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Peak Concurrent Sessions</span>
                            <span class="text-sm text-gray-900" id="peak-sessions">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent CSRF Errors Table -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <div class="sm:flex sm:items-center sm:justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent CSRF Errors</h3>
                    <div class="mt-3 sm:mt-0 sm:ml-4">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="refreshCsrfErrors()">
                            <svg class="-ml-0.5 mr-2 h-4 w-4 lucide lucide-refresh-cw" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                <path d="M21 3v5h-5"/>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                <path d="M3 21v-5h5"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody id="csrf-errors-table" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading recent errors...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Security Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="clearOldSessions()">
                        <svg class="-ml-1 mr-2 h-4 w-4 lucide lucide-trash-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            <line x1="10" x2="10" y1="11" y2="17"/>
                            <line x1="14" x2="14" y1="11" y2="17"/>
                        </svg>
                        Clear Old Sessions
                    </button>
                    
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="exportSecurityReport()">
                        <svg class="-ml-1 mr-2 h-4 w-4 lucide lucide-download" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" x2="12" y1="15" y2="3"/>
                        </svg>
                        Export Report
                    </button>
                    
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="refreshAllData()">
                        <svg class="-ml-1 mr-2 h-4 w-4 lucide lucide-refresh-cw" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                            <path d="M21 3v5h-5"/>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                            <path d="M3 21v-5h5"/>
                        </svg>
                        Refresh All
                    </button>
                    
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="testSecurity()">
                        <svg class="-ml-1 mr-2 h-4 w-4 lucide lucide-shield-check" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                        Test Security
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Security Monitor -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let csrfChart = null;

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    refreshAllData();
    
    // Set up automatic refresh every 30 seconds
    setInterval(refreshAllData, 30000);
    
    // Update timestamp every second
    setInterval(updateLastUpdatedTime, 1000);
    
    // Initialize chart
    initCsrfTimelineChart();
});

function initCsrfTimelineChart() {
    const ctx = document.getElementById('csrf-timeline-chart');
    if (!ctx) return;
    
    csrfChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'CSRF Errors',
                data: [],
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(239, 68, 68)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
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
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 6,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Errors: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
    
    // Load initial chart data
    loadCsrfChartData();
}

function refreshAllData() {
    loadDashboardStats();
    loadCsrfErrors();
    loadSessionStats();
    loadCsrfChartData();
    updateLastUpdatedTime();
}

function loadCsrfChartData() {
    fetch('/admin/security-monitor/csrf-errors')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (csrfChart && data.errors) {
                // Group errors by hour
                const hourlyData = {};
                const now = new Date();
                
                // Initialize last 24 hours with 0
                for (let i = 23; i >= 0; i--) {
                    const hour = new Date(now - i * 3600000);
                    const hourLabel = hour.getHours().toString().padStart(2, '0') + ':00';
                    hourlyData[hourLabel] = 0;
                }
                
                // Count errors per hour
                data.errors.forEach(error => {
                    const errorDate = new Date(error.timestamp);
                    const hourLabel = errorDate.getHours().toString().padStart(2, '0') + ':00';
                    if (hourlyData.hasOwnProperty(hourLabel)) {
                        hourlyData[hourLabel]++;
                    }
                });
                
                // Update chart
                const labels = Object.keys(hourlyData);
                const values = Object.values(hourlyData);
                
                csrfChart.data.labels = labels;
                csrfChart.data.datasets[0].data = values;
                csrfChart.update('none'); // Update without animation for better performance
            }
        })
        .catch(error => {
            console.error('Error loading CSRF chart data:', error);
        });
}

function updateLastUpdatedTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: 'numeric', 
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('last-updated').textContent = timeString;
}

function loadDashboardStats() {
    // Show loading state
    document.getElementById('csrf-errors-today').innerHTML = '<div class="animate-pulse w-8 h-4 bg-gray-300 rounded"></div>';
    document.getElementById('active-sessions').innerHTML = '<div class="animate-pulse w-8 h-4 bg-gray-300 rounded"></div>';
    document.getElementById('security-alerts').innerHTML = '<div class="animate-pulse w-8 h-4 bg-gray-300 rounded"></div>';
    document.getElementById('session-health').innerHTML = '<div class="animate-pulse w-12 h-4 bg-gray-300 rounded"></div>';
    
    fetch('/admin/security-monitor/stats')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('csrf-errors-today').textContent = data.csrf_errors_today || '0';
            document.getElementById('active-sessions').textContent = data.active_sessions || '0';
            document.getElementById('security-alerts').textContent = data.security_alerts || '0';
            document.getElementById('session-health').textContent = data.session_health || 'Good';
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
            document.getElementById('csrf-errors-today').textContent = 'Error';
            document.getElementById('active-sessions').textContent = 'Error';
            document.getElementById('security-alerts').textContent = 'Error';
            document.getElementById('session-health').textContent = 'Error';
        });
}

function loadCsrfErrors() {
    const tbody = document.getElementById('csrf-errors-table');
    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center"><div class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading CSRF errors...</div></td></tr>';
    
    fetch('/admin/security-monitor/csrf-errors')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';
            
            if (data.errors && data.errors.length > 0) {
                data.errors.forEach(error => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.timestamp}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.ip || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 max-w-xs truncate" title="${error.url || 'N/A'}">${error.url || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate" title="${error.user_agent || 'N/A'}">${error.user_agent ? error.user_agent.substring(0, 50) + '...' : 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                CSRF Error
                            </span>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500"><div class="flex items-center justify-center"><svg class="w-5 h-5 mr-2 text-green-500 lucide lucide-shield-check" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>No recent CSRF errors found - system is secure!</div></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading CSRF errors:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500"><div class="flex items-center justify-center"><svg class="w-5 h-5 mr-2 lucide lucide-alert-triangle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="m12 17 .01 0"/></svg>Error loading CSRF error data</div></td></tr>';
        });
}

function loadSessionStats() {
    // Show loading states
    document.getElementById('total-sessions-24h').innerHTML = '<div class="animate-pulse w-8 h-4 bg-gray-300 rounded"></div>';
    document.getElementById('expired-sessions').innerHTML = '<div class="animate-pulse w-8 h-4 bg-gray-300 rounded"></div>';
    document.getElementById('avg-session-duration').innerHTML = '<div class="animate-pulse w-16 h-4 bg-gray-300 rounded"></div>';
    document.getElementById('peak-sessions').innerHTML = '<div class="animate-pulse w-8 h-4 bg-gray-300 rounded"></div>';
    
    fetch('/admin/security-monitor/session-stats')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('total-sessions-24h').textContent = data.total_sessions_24h || '0';
            document.getElementById('expired-sessions').textContent = data.expired_sessions || '0';
            document.getElementById('avg-session-duration').textContent = data.avg_session_duration || '0 min';
            document.getElementById('peak-sessions').textContent = data.peak_sessions || '0';
        })
        .catch(error => {
            console.error('Error loading session stats:', error);
            document.getElementById('total-sessions-24h').textContent = 'Error';
            document.getElementById('expired-sessions').textContent = 'Error';
            document.getElementById('avg-session-duration').textContent = 'Error';
            document.getElementById('peak-sessions').textContent = 'Error';
        });
}

function refreshCsrfErrors() {
    loadCsrfErrors();
}

function clearOldSessions() {
    if (confirm('Are you sure you want to clear old sessions? This will log out inactive users.')) {
        fetch('/admin/security-monitor/clear-sessions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Sessions cleared successfully');
            refreshAllData();
        })
        .catch(error => {
            console.error('Error clearing sessions:', error);
            alert('Error clearing sessions');
        });
    }
}

function exportSecurityReport() {
    window.open('/admin/security-monitor/export', '_blank');
}

function testSecurity() {
    fetch('/admin/security-monitor/test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Security test completed: ' + (data.message || 'All tests passed'));
    })
    .catch(error => {
        console.error('Error running security test:', error);
        alert('Error running security test');
    });
}
</script>
@endsection