// Dashboard JavaScript functionality
let currentPeriod = 'weekly';
let currentRankingPeriod = 'weekly';

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Set up event listeners
    setupEventListeners();
    
    // Load initial data
    loadDashboardData();
}

function setupEventListeners() {
    // Period tab listeners are handled by onclick attributes in HTML
    // You can add more event listeners here as needed
}

// Change main period (Weekly, Quarterly, Yearly)
function changePeriod(period) {
    currentPeriod = period;
    
    // Update active tab
    document.querySelectorAll('.period-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.period === period) {
            tab.classList.add('active');
        }
    });
    
    // Load new data
    loadMetricsData(period);
}

// Change ranking period (Weekly, Quarterly)
function changeRankingPeriod(period) {
    currentRankingPeriod = period;
    
    // Update active tab
    document.querySelectorAll('.ranking-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.period === period) {
            tab.classList.add('active');
        }
    });
    
    // Load new ranking data
    loadTeamRankings(period);
}

// Load dashboard data
function loadDashboardData() {
    loadMetricsData(currentPeriod);
    loadTeamRankings(currentRankingPeriod);
}

// Load metrics data via AJAX
function loadMetricsData(period) {
    showLoading('metrics');
    
    fetch(`/dashboard/metrics?period=${period}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateMetricsDisplay(data.metrics);
        } else {
            showError('Failed to load metrics data');
        }
    })
    .catch(error => {
        console.error('Error loading metrics:', error);
        showError('Failed to load metrics data');
    })
    .finally(() => {
        hideLoading('metrics');
    });
}

// Load team rankings data via AJAX
function loadTeamRankings(period) {
    showLoading('rankings');
    
    fetch(`/dashboard/team-rankings?period=${period}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateTeamRankingsDisplay(data.teamMembers);
        } else {
            showError('Failed to load team rankings');
        }
    })
    .catch(error => {
        console.error('Error loading team rankings:', error);
        showError('Failed to load team rankings');
    })
    .finally(() => {
        hideLoading('rankings');
    });
}

// Sync data from external APIs
function syncData() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 animate-spin"></i><span class="hidden sm:inline ml-2">Syncing...</span>';
    button.disabled = true;
    
    fetch('/dashboard/sync', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Data synchronized successfully!');
            // Reload dashboard data
            loadDashboardData();
        } else {
            showError(data.message || 'Failed to sync data');
        }
    })
    .catch(error => {
        console.error('Error syncing data:', error);
        showError('Failed to sync data');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
        
        // Reinitialize icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
}

// Export data
function exportData() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i data-lucide="download" class="w-4 h-4"></i><span class="hidden sm:inline ml-2">Exporting...</span>';
    button.disabled = true;
    
    fetch('/dashboard/export', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            period: currentPeriod,
            format: 'json'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Data exported successfully!');
            // You can trigger a download here if needed
            if (data.downloadUrl) {
                window.open(data.downloadUrl, '_blank');
            }
        } else {
            showError('Failed to export data');
        }
    })
    .catch(error => {
        console.error('Error exporting data:', error);
        showError('Failed to export data');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
        
        // Reinitialize icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
}

// Open settings (placeholder function)
function openSettings() {
    // Implement settings modal or redirect to settings page
    alert('Settings functionality to be implemented');
}

// Send congratulations to team member
function sendCongratulations(memberId) {
    const button = event.target;
    const originalText = button.textContent;
    
    // Show loading state
    button.textContent = 'Sending...';
    button.disabled = true;
    
    fetch('/dashboard/congratulations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            member_id: memberId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            button.textContent = 'Sent!';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-green-600');
        } else {
            showError('Failed to send congratulations');
            button.textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error sending congratulations:', error);
        showError('Failed to send congratulations');
        button.textContent = originalText;
        button.disabled = false;
    });
}

// Update metrics display
function updateMetricsDisplay(metrics) {
    // This function would update the metrics cards with new data
    // Implementation depends on your specific needs
    console.log('Updating metrics:', metrics);
}

// Update team rankings display
function updateTeamRankingsDisplay(teamMembers) {
    // This function would update the team rankings with new data
    // Implementation depends on your specific needs
    console.log('Updating team rankings:', teamMembers);
}

// Utility functions
function showLoading(section) {
    const element = document.querySelector(`[data-section="${section}"]`);
    if (element) {
        element.classList.add('loading');
    }
}

function hideLoading(section) {
    const element = document.querySelector(`[data-section="${section}"]`);
    if (element) {
        element.classList.remove('loading');
    }
}

function showSuccess(message) {
    // Implement your success notification system
    // This could be a toast, modal, or other notification method
    console.log('Success:', message);
    
    // Simple alert for now - replace with your preferred notification system
    alert(message);
}

function showError(message) {
    // Implement your error notification system
    // This could be a toast, modal, or other notification method
    console.error('Error:', message);
    
    // Simple alert for now - replace with your preferred notification system
    alert('Error: ' + message);
}

// Animation helpers
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Progress bar animation
function animateProgressBar(element, targetWidth) {
    element.style.width = '0%';
    setTimeout(() => {
        element.style.width = targetWidth + '%';
    }, 100);
}

// Refresh dashboard data periodically (optional)
function startAutoRefresh(intervalMinutes = 5) {
    setInterval(() => {
        loadDashboardData();
    }, intervalMinutes * 60 * 1000);
}

// Initialize auto-refresh (uncomment if needed)
// startAutoRefresh(5); // Refresh every 5 minutes