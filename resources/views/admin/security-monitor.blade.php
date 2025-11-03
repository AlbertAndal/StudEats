@extends('layouts.admin')

@section('title', 'Security Monitor')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Security Monitor</h1>
            <p class="mt-2 text-gray-600">Monitor CSRF errors, session health, and security events</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- CSRF Errors Today -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
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
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
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
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM5 7h5l5-5v5H5z"></path>
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
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                        <div id="csrf-timeline-chart" class="w-full h-full">
                            <p class="text-gray-500">Loading chart...</p>
                        </div>
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
                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
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
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear Old Sessions
                    </button>
                    
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="exportSecurityReport()">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Report
                    </button>
                    
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="refreshAllData()">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh All
                    </button>
                    
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="testSecurity()">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Test Security
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Security Monitor -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    refreshAllData();
    
    // Set up automatic refresh every 30 seconds
    setInterval(refreshAllData, 30000);
});

function refreshAllData() {
    loadDashboardStats();
    loadCsrfErrors();
    loadSessionStats();
}

function loadDashboardStats() {
    fetch('/admin/security-monitor/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('csrf-errors-today').textContent = data.csrf_errors_today || '0';
            document.getElementById('active-sessions').textContent = data.active_sessions || '0';
            document.getElementById('security-alerts').textContent = data.security_alerts || '0';
            document.getElementById('session-health').textContent = data.session_health || 'Good';
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
        });
}

function loadCsrfErrors() {
    fetch('/admin/security-monitor/csrf-errors')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('csrf-errors-table');
            tbody.innerHTML = '';
            
            if (data.errors && data.errors.length > 0) {
                data.errors.forEach(error => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.timestamp}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.ip || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.url || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${error.user_agent ? error.user_agent.substring(0, 50) + '...' : 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Error
                            </span>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent CSRF errors found</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading CSRF errors:', error);
            document.getElementById('csrf-errors-table').innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Error loading data</td></tr>';
        });
}

function loadSessionStats() {
    fetch('/admin/security-monitor/session-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-sessions-24h').textContent = data.total_sessions_24h || '0';
            document.getElementById('expired-sessions').textContent = data.expired_sessions || '0';
            document.getElementById('avg-session-duration').textContent = data.avg_session_duration || '0 min';
            document.getElementById('peak-sessions').textContent = data.peak_sessions || '0';
        })
        .catch(error => {
            console.error('Error loading session stats:', error);
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