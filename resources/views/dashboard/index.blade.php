@extends('layouts.index')
@section('title','Dashboard')

@section('content')
<div id="proBanner" class="mb-8 hidden">
    <div class="rounded-2xl p-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold">Upgrade to Pro 🚀</h2>
            <p class="text-sm opacity-90 mt-1">
                Unlock analytics, campaigns, QR tools, WhatsApp automation & more.
            </p>
        </div>
        <button onclick="showPlanModal()" class="bg-white text-indigo-600 px-6 py-2.5 rounded-xl font-semibold hover:scale-105 transition">
            Get Pro
        </button>
    </div>
</div>

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Executive Overview</h1>
            <p class="text-gray-500 text-sm mt-1">Monitor your social media performance at a glance</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span>Live Data</span>
            </div>
            <button class="px-4 py-2 bg-gray-50 hover:bg-gray-100 border rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-filter text-gray-400"></i>
                <span class="text-sm font-medium text-gray-700">Filters</span>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <select id="platformSelect" class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all appearance-none">
                        <option value="all">All Platforms</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="youtube">YouTube</option>
                    </select>
                    <i class="fas fa-globe absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>

                <div class="relative">
                    <select id="rangeSelect" class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        <option value="today">Today</option>
                        <option value="7">Last 7 Days</option>
                        <option value="30" selected>Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>

                <div class="relative hidden" id="pageFilterWrapper">
                    <select id="pageSelect" class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        <option value="all">All Pages</option>
                    </select>
                    <i class="fas fa-flag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>

                @if($hasInstagram && $instagramAccounts->count() > 0)
                <div class="relative hidden" id="instagramFilterWrapper">
                    <select id="instagramProfileSelect" class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-100 transition-all">
                        <option value="all">All Instagram Profiles ({{ $instagramAccounts->count() }})</option>
                        @foreach($instagramAccounts as $profile)
                            <option value="{{ $profile->_id }}">{{ $profile->credentials['username'] ?? 'Instagram Profile' }}</option>
                        @endforeach
                    </select>
                    <i class="fab fa-instagram absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-400 text-sm"></i>
                </div>
                @endif

                @if($hasYoutube && $youtubeAccounts->count() > 0)
                <div class="relative hidden" id="youtubeFilterWrapper">
                    <select id="youtubeChannelSelect" class="pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-red-500 focus:ring-2 focus:ring-red-100 transition-all">
                        <option value="all">All YouTube Channels ({{ $youtubeAccounts->count() }})</option>
                        @foreach($youtubeAccounts as $channel)
                            <option value="{{ $channel->_id }}">{{ $channel->credentials['channel_name'] ?? 'YouTube Channel' }}</option>
                        @endforeach
                    </select>
                    <i class="fab fa-youtube absolute left-3 top-1/2 transform -translate-y-1/2 text-red-400 text-sm"></i>
                </div>
                @endif

                <button onclick="loadDashboard()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    <span>Apply</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl border border-blue-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-blue-700 mb-1">Total Reach</p>
                <h3 id="totalReach" class="text-3xl font-bold text-gray-900">0</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-eye text-blue-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> 12.5%
            </span>
            <span class="text-gray-500 ml-2">vs previous period</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl border border-purple-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-purple-700 mb-1">Total Engagement</p>
                <h3 id="totalEngagement" class="text-3xl font-bold text-gray-900">0</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-heart text-purple-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> 8.3%
            </span>
            <span class="text-gray-500 ml-2">vs previous period</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl border border-emerald-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-emerald-700 mb-1">Link Clicks</p>
                <h3 id="totalClicks" class="text-3xl font-bold text-gray-900">0</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                <i class="fas fa-link text-emerald-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> 15.2%
            </span>
            <span class="text-gray-500 ml-2">vs previous period</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-amber-50 to-white rounded-2xl border border-amber-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-amber-700 mb-1">Follower Growth</p>
                <h3 id="followerGrowth" class="text-3xl font-bold text-gray-900">0</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                <i class="fas fa-users text-amber-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> 5.7%
            </span>
            <span class="text-gray-500 ml-2">vs previous period</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border p-6 hover:shadow-sm transition-shadow">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Engagement Trends</h3>
                <p class="text-sm text-gray-500">Engagement over selected period</p>
            </div>
            <div class="flex gap-2" id="engagementTrendsButtons">
                <button data-metric="reach" class="metric-btn px-3 py-1.5 text-sm rounded-lg bg-blue-50 text-blue-700 font-medium transition-all">
                    📊 Reach
                </button>
                <button data-metric="likes" class="metric-btn px-3 py-1.5 text-sm rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                    ❤️ Likes
                </button>
                <button data-metric="shares" class="metric-btn px-3 py-1.5 text-sm rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                    🔄 Shares
                </button>
            </div>
        </div>
        <div class="relative h-80">
            <canvas id="engagementChart"></canvas>
            <div id="noEngagementData" class="hidden absolute inset-0 flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 font-medium">No engagement data available</p>
                <p class="text-gray-400 text-sm mt-1">Try selecting a different time range</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border p-6 hover:shadow-sm transition-shadow">
        <div class="mb-6">
            <h3 class="font-bold text-lg text-gray-900">Platform Performance</h3>
            <p class="text-sm text-gray-500">Comparison across platforms</p>
        </div>
        <div class="h-80">
            <canvas id="platformChart"></canvas>
        </div>
        <div class="mt-6 pt-6 border-t">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-gray-600">Reach</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                    <span class="text-gray-600">Engagement</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-lg text-gray-900">Recent Activity</h3>
            <p class="text-sm text-gray-500">Latest updates from your platforms</p>
        </div>
        <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All →</button>
    </div>
    
    <div id="recentActivity" class="space-y-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
let engagementChart, platformChart;
let pagesLoaded = false;
let currentMetric = 'reach';

document.addEventListener('DOMContentLoaded', () => {
    engagementChart = new Chart(document.getElementById('engagementChart'), {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Engagement',
            data: [],
            fill: true,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                titleColor: '#1F2937',
                bodyColor: '#4B5563',
                borderColor: '#E5E7EB',
                borderWidth: 1,
                padding: 12,
                boxPadding: 6
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: null,  // Auto-scale based on data
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: { 
                    color: '#6B7280',
                    stepSize: 1,  // Step size 1 for small numbers
                    callback: function(val) { return val; }
                }
            },
            x: {
                grid: { display: false },
                ticks: { 
                    color: '#6B7280',
                    maxRotation: 45,
                    minRotation: 45,
                    autoSkip: true,
                    maxTicksLimit: 8
                }
            }
        }
    }
});
    platformChart = new Chart(document.getElementById('platformChart'), {
        type: 'bar',
        data: {
            labels: ['Facebook', 'Instagram', 'YouTube'],
            datasets: [
                {
                    label: 'Reach',
                    data: [0, 0, 0],
                    backgroundColor: '#3B82F6',
                    borderRadius: 6,
                    barPercentage: 0.6
                },
                {
                    label: 'Engagement',
                    data: [0, 0, 0],
                    backgroundColor: '#8B5CF6',
                    borderRadius: 6,
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#1F2937',
                    bodyColor: '#4B5563',
                    borderColor: '#E5E7EB',
                    borderWidth: 1
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { color: '#6B7280' }
                },
                y: {
                    grid: { display: false },
                    ticks: { color: '#6B7280' }
                }
            }
        }
    });

    document.getElementById('platformSelect').addEventListener('change', updateFilterVisibility);
    document.getElementById('platformSelect').addEventListener('change', loadDashboard);
    document.getElementById('rangeSelect').addEventListener('change', loadDashboard);
    document.getElementById('pageSelect').addEventListener('change', loadDashboard);
    document.getElementById('instagramProfileSelect')?.addEventListener('change', loadDashboard);
    document.getElementById('youtubeChannelSelect')?.addEventListener('change', loadDashboard);

    document.querySelectorAll('.metric-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            document.querySelectorAll('.metric-btn').forEach(b => {
                b.classList.remove('bg-blue-50', 'text-blue-700');
                b.classList.add('bg-gray-100', 'text-gray-600');
            });
            this.classList.remove('bg-gray-100', 'text-gray-600');
            this.classList.add('bg-blue-50', 'text-blue-700');
            
            currentMetric = this.dataset.metric;
            await loadDashboard();
        });
    });

    loadDashboard();
});

function updateFilterVisibility() {
    const platform = document.getElementById('platformSelect').value;
    
    const pageFilter = document.getElementById('pageFilterWrapper');
    const instaFilter = document.getElementById('instagramFilterWrapper');
    const ytFilter = document.getElementById('youtubeFilterWrapper');
    
    if (pageFilter) pageFilter.classList.add('hidden');
    if (instaFilter) instaFilter.classList.add('hidden');
    if (ytFilter) ytFilter.classList.add('hidden');
    
    if (platform === 'facebook') {
        if (pageFilter) pageFilter.classList.remove('hidden');
    } else if (platform === 'instagram') {
        if (instaFilter) instaFilter.classList.remove('hidden');
    } else if (platform === 'youtube') {
        if (ytFilter) ytFilter.classList.remove('hidden');
    }
    
    if (pageFilter?.querySelector('select')) pageFilter.querySelector('select').value = 'all';
    if (instaFilter?.querySelector('select')) instaFilter.querySelector('select').value = 'all';
    if (ytFilter?.querySelector('select')) ytFilter.querySelector('select').value = 'all';
}

async function loadDashboard() {
    const platform = document.getElementById('platformSelect').value;
    const range = document.getElementById('rangeSelect').value;
    const page = document.getElementById('pageSelect')?.value || 'all';
    const instagramProfile = document.getElementById('instagramProfileSelect')?.value || 'all';
    const youtubeChannel = document.getElementById('youtubeChannelSelect')?.value || 'all';

    document.querySelectorAll('.text-3xl').forEach(el => {
        el.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    });

    let url = `{{ url('dashboard/live') }}?platform=${platform}&page=${page}&instagram_profile=${instagramProfile}&youtube_channel=${youtubeChannel}&metric=${currentMetric}`;
    if (range === 'today') url += '&filter=today';
    else url += `&range=${range}`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            updateStats(data);
            updateCharts(data);
            updatePageFilter(data);
            updateRecentActivity(data);
        } else {
            showErrorState();
        }
    } catch (error) {
        console.error('Dashboard fetch error:', error);
        showErrorState();
    }
}

function updateStats(data) {
    const formatNumber = (num) => {
        if (!num && num !== 0) return '0';
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    document.getElementById('totalReach').textContent = formatNumber(data.totalReach);
    document.getElementById('totalEngagement').textContent = formatNumber(data.totalEngagement);
    document.getElementById('followerGrowth').textContent = formatNumber(data.followerGrowth);
    document.getElementById('totalClicks').textContent = formatNumber(data.totalClicks || 0);
}

function updateCharts(data) {
    if (!data.labels || data.labels.length === 0) {
        document.getElementById('noEngagementData').classList.remove('hidden');
    } else {
        document.getElementById('noEngagementData').classList.add('hidden');
        engagementChart.data.labels = data.labels;
        
        let chartData = [];
        if (currentMetric === 'reach') {
            chartData = data.reachData || [];
        } else if (currentMetric === 'likes') {
            chartData = data.likesData || [];
        } else if (currentMetric === 'shares') {
            chartData = data.sharesData || [];
        }
        
        engagementChart.data.datasets[0].data = chartData;
        engagementChart.update();
    }
    
    if (data.platformReach && data.platformEngagement) {
        platformChart.data.datasets[0].data = data.platformReach;
        platformChart.data.datasets[1].data = data.platformEngagement;
        platformChart.update();
    }
}

function updatePageFilter(data) {
    const pageFilterWrapper = document.getElementById('pageFilterWrapper');
    const pageSelect = document.getElementById('pageSelect');
    const platform = document.getElementById('platformSelect').value;

    if ((platform === 'facebook' || platform === 'all') && data.pages?.length) {
        pageFilterWrapper.classList.remove('hidden');

        if (!pagesLoaded) {
            pageSelect.innerHTML = '<option value="all">All Pages</option>';
            data.pages.forEach(page => {
                const option = document.createElement('option');
                option.value = page.page_id;
                option.textContent = page.page_name;
                pageSelect.appendChild(option);
            });
            pagesLoaded = true;
        }
    } else {
        pageFilterWrapper.classList.add('hidden');
        if (pageSelect) pageSelect.value = 'all';
    }
}

function updateRecentActivity(data) {
    const container = document.getElementById('recentActivity');
    
    if (!data.recentActivity || data.recentActivity.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">No recent activity</p>
            </div>
        `;
        return;
    }

    container.innerHTML = '';
    
    data.recentActivity.forEach(activity => {
        const timeAgo = getTimeAgo(activity.timestamp);
        let postId = activity.id || activity._id || '';
        
        let mediaTypeIcon = '';
        if (activity.platform === 'instagram') {
            mediaTypeIcon = '<i class="fab fa-instagram text-pink-400"></i>';
        } else if (activity.platform === 'youtube') {
            mediaTypeIcon = '<i class="fab fa-youtube text-red-400"></i>';
        } else {
            mediaTypeIcon = '<i class="fab fa-facebook-f text-blue-400"></i>';
        }
        
        let previewHtml = '';
        if (activity.media_url) {
            const isVideo = activity.media_url.includes('.mp4') || activity.media_url.includes('.mov');
            
            if (isVideo) {
                previewHtml = `
                    <div class="relative w-20 h-20 rounded-lg overflow-hidden bg-gray-100">
                        <video src="${activity.media_url}" class="w-full h-full object-cover"></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <i class="fas fa-play text-white text-xl"></i>
                        </div>
                    </div>
                `;
            } else {
                previewHtml = `
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100">
                        <img src="${activity.media_url}" class="w-full h-full object-cover">
                    </div>
                `;
            }
        } else {
            previewHtml = `
                <div class="w-12 h-12 rounded-lg ${activity.bg_color || 'bg-gray-100'} flex items-center justify-center text-2xl">
                    ${mediaTypeIcon}
                </div>
            `;
        }
        
        const activityHTML = `
            <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer" 
                onclick="window.location.href = '/post/${postId}'">
                <div class="flex-shrink-0">
                    ${previewHtml}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-medium ${activity.text_color || 'text-gray-600'}">${activity.platform_name || activity.platform}</span>
                    </div>
                    <p class="text-sm text-gray-900 font-medium truncate">${activity.title || 'Untitled'}</p>
                    <p class="text-xs text-gray-500 mt-1">${activity.description || ''}</p>
                </div>
                <div class="text-xs text-gray-400 flex-shrink-0">${timeAgo}</div>
            </div>
        `;
        
        container.innerHTML += activityHTML;
    });
}

function getTimeAgo(timestamp) {
    const now = new Date();
    const postTime = new Date(timestamp);
    const diff = Math.floor((now - postTime) / 1000);
    const minutes = Math.floor(diff / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes} min ago`;
    if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    return `${days} day${days > 1 ? 's' : ''} ago`;
}

function showErrorState() {
    document.querySelectorAll('.text-3xl').forEach(el => {
        el.textContent = '--';
    });
    
    document.getElementById('noEngagementData').classList.remove('hidden');
    document.getElementById('noEngagementData').innerHTML = `
        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
        </div>
        <p class="text-gray-500 font-medium">Failed to load data</p>
        <button onclick="loadDashboard()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
            Try Again
        </button>
    `;
}
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.isProUser) {
        document.getElementById('proBanner')?.classList.remove('hidden');
    }
});
</script>

@endsection