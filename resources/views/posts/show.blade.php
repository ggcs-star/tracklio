@extends('layouts.index')

@section('title', 'Post Analytics')

@section('content')
<style>
.omnipost-container {
    display: flex;
    gap: 24px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.omnipost-sidebar {
    width: 320px;
    flex-shrink: 0;
}

.omnipost-sidebar-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.omnipost-sidebar-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
    font-size: 16px;
    color: #1e293b;
}

.omnipost-post-list {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.omnipost-post-item {
    display: flex;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.2s;
}

.omnipost-post-item.active {
    background: #f0f7ff;
    border-left: 3px solid #0d6efd;
}

.omnipost-post-item:hover {
    background: #f8f9fa;
}

.omnipost-post-thumb {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
}

.omnipost-post-thumb-inner {
    width: 100%;
    height: 100%;
    background: #f1f3f5;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.omnipost-post-thumb-inner img,
.omnipost-post-thumb-inner video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-overlay-small {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.6);
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.play-overlay-small i {
    font-size: 8px;
    color: white;
    margin-left: 2px;
}

.omnipost-post-info {
    flex: 1;
    min-width: 0;
}

.omnipost-post-title {
    font-weight: 500;
    font-size: 14px;
    color: #1e293b;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.omnipost-post-date {
    font-size: 11px;
    color: #94a3b8;
}

.omnipost-main {
    flex: 1;
    min-width: 0;
}

.omnipost-header-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.omnipost-header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
}

.omnipost-title-section {
    flex: 1;
}

.omnipost-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
}

.omnipost-date {
    font-size: 13px;
    color: #94a3b8;
}

.omnipost-stats-badge {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.omnipost-stat-icon {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #64748b;
}

.omnipost-stat-icon i {
    font-size: 16px;
}

.omnipost-status-badge {
    background: #e6f7e6;
    color: #2e7d32;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
}

.omnipost-header-bottom {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.omnipost-thumb-large {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
}

.omnipost-thumb-large-inner {
    width: 100%;
    height: 100%;
    background: #f1f3f5;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}

.omnipost-thumb-large-inner img,
.omnipost-thumb-large-inner video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-overlay-large {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.play-overlay-large i {
    font-size: 14px;
    color: white;
    margin-left: 2px;
}

.omnipost-caption {
    flex: 1;
    font-size: 14px;
    color: #475569;
    line-height: 1.5;
}

.omnipost-media-card {
    margin-bottom: 24px;
}

.omnipost-media-inner {
    background: #f8f9fa;
    border-radius: 16px;
    overflow: hidden;
}

.omnipost-media-inner img,
.omnipost-media-inner video {
    width: 100%;
    max-height: 450px;
    object-fit: contain;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2px;
}

.media-grid-item {
    position: relative;
    aspect-ratio: 1 / 1;
    background: #000;
}

.media-grid-item img,
.media-grid-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-overlay-grid {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.play-overlay-grid i {
    font-size: 18px;
    color: white;
    margin-left: 2px;
}

.omnipost-stats-row {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.omnipost-stat-card {
    flex: 1;
    min-width: 100px;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    padding: 20px 16px;
    text-align: center;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.omnipost-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.omnipost-stat-icon-lg {
    font-size: 28px;
    margin-bottom: 12px;
    display: inline-block;
}

.omnipost-stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
}

.omnipost-stat-label {
    font-size: 13px;
    color: #94a3b8;
}

.omnipost-toggle {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.omnipost-toggle-btn {
    background: none;
    border: none;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.2s;
}

.omnipost-toggle-btn.active {
    background: #0d6efd;
    color: #fff;
}

.omnipost-toggle-btn:not(.active) {
    color: #64748b;
}

.omnipost-toggle-btn:not(.active):hover {
    background: #f1f3f5;
}

.omnipost-chart-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e9ecef;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.omnipost-chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.omnipost-chart-title {
    font-weight: 600;
    color: #1e293b;
}

.omnipost-chart-subtitle {
    font-size: 12px;
    color: #94a3b8;
}

.omnipost-chart-container {
    height: 280px;
    position: relative;
}

.omnipost-post-list::-webkit-scrollbar {
    width: 4px;
}

.omnipost-post-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.omnipost-post-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

@media (max-width: 992px) {
    .omnipost-container {
        flex-direction: column;
    }
    .omnipost-sidebar {
        width: 100%;
    }
    .omnipost-post-list {
        max-height: 300px;
    }
    .omnipost-stats-row {
        flex-wrap: wrap;
    }
    .omnipost-stat-card {
        min-width: calc(33% - 12px);
    }
}

@media (max-width: 768px) {
    .omnipost-header-top {
        flex-direction: column;
        gap: 12px;
    }
    .omnipost-stats-badge {
        flex-wrap: wrap;
    }
    .omnipost-stat-card {
        min-width: calc(50% - 12px);
    }
}
</style>

<div class="omnipost-container">
    
    <div class="omnipost-sidebar">
        <div class="omnipost-sidebar-card">
            <div class="omnipost-sidebar-header">
                📋 Posts
            </div>
            <div class="omnipost-post-list">
                @php
                    $userPosts = App\Models\Post::where('user_id', (string) auth()->id())
                        ->where('status', 'published')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                @endphp
                
                @foreach($userPosts as $userPost)
                <div class="omnipost-post-item {{ $userPost->_id == $post->_id ? 'active' : '' }}" 
                     onclick="window.location.href='/post/{{ $userPost->_id }}'">
                    <div class="omnipost-post-thumb">
                        <div class="omnipost-post-thumb-inner">
                            @if($userPost->media_path)
                                @php
                                    $ext = pathinfo($userPost->media_path, PATHINFO_EXTENSION);
                                    $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'mkv']);
                                @endphp
                                @if($isVideo)
                                    <video src="{{ asset('storage/' . $userPost->media_path) }}"></video>
                                    <div class="play-overlay-small">
                                        <i class="fas fa-play"></i>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/' . $userPost->media_path) }}" alt="thumb">
                                @endif
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-secondary"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="omnipost-post-info">
                        <div class="omnipost-post-title">{{ Str::limit(strip_tags($userPost->content ?? 'Untitled'), 40) }}</div>
                        <div class="omnipost-post-date">{{ $userPost->created_at->format('d M Y, g:i A') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="omnipost-main">
        
        <div class="omnipost-header-card">
            <div class="omnipost-header-top">
                <div class="omnipost-title-section">
                    <div class="omnipost-title">{{ Str::limit(strip_tags($post->content ?? 'No content'), 60) }}</div>
                    <div class="omnipost-date">Posted on {{ $post->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                <div class="omnipost-stats-badge">
                    <div class="omnipost-stat-icon">
                        <i class="far fa-eye"></i>
                        <span>{{ number_format($reactions ?? 0) }}</span>
                    </div>
                    <div class="omnipost-stat-icon">
                        <i class="far fa-heart"></i>
                        <span>{{ number_format($reactions ?? 0) }}</span>
                    </div>
                    <div class="omnipost-stat-icon">
                        <i class="far fa-comment"></i>
                        <span>{{ number_format($comments ?? 0) }}</span>
                    </div>
                    <div class="omnipost-status-badge">
                        <i class="fas fa-check-circle me-1"></i> Posted
                    </div>
                </div>
            </div>
            <div class="omnipost-header-bottom">
                <div class="omnipost-thumb-large">
                    <div class="omnipost-thumb-large-inner">
                        @if(!empty($mediaUrls[0]))
                            @php
                                $isVideo = str_contains($mediaUrls[0], '.mp4') || str_contains($mediaUrls[0], '.mov');
                            @endphp
                            @if($isVideo)
                                <video src="{{ $mediaUrls[0] }}"></video>
                                <div class="play-overlay-large">
                                    <i class="fas fa-play"></i>
                                </div>
                            @else
                                <img src="{{ $mediaUrls[0] }}" alt="post image">
                            @endif
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image text-secondary fs-3"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="omnipost-caption">
                    {{ Str::limit(strip_tags($post->content ?? 'No description provided'), 150) }}
                </div>
            </div>
        </div>
        
        <!-- @if(!empty($mediaUrls))
        <div class="omnipost-media-card">
            <div class="omnipost-media-inner">
                @if(count($mediaUrls) === 1)
                    @php
                        $isVideo = str_contains($mediaUrls[0], '.mp4') || str_contains($mediaUrls[0], '.mov');
                    @endphp
                    @if($isVideo)
                        <div style="position: relative;">
                            <video src="{{ $mediaUrls[0] }}" controls style="width: 100%; max-height: 450px; object-fit: contain;"></video>
                        </div>
                    @else
                        <img src="{{ $mediaUrls[0] }}" style="width: 100%; max-height: 450px; object-fit: cover;">
                    @endif
                @else
                    <div class="media-grid">
                        @foreach($mediaUrls as $url)
                            <div class="media-grid-item">
                                @php
                                    $isVideo = str_contains($url, '.mp4') || str_contains($url, '.mov');
                                @endphp
                                @if($isVideo)
                                    <video src="{{ $url }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                                    <div class="play-overlay-grid">
                                        <i class="fas fa-play"></i>
                                    </div>
                                @else
                                    <img src="{{ $url }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif -->
        
        <div class="omnipost-stats-row">
    @if($platform === 'youtube')
    <!-- YouTube Stats -->
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-eye"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($reactions ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Views</div>
    </div>
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-heart"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($reactions ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Likes</div>
    </div>
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-comment"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($comments ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Comments</div>
    </div>
    @elseif($platform === 'instagram')
    <!-- Instagram Stats -->
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-heart"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($reactions ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Likes</div>
    </div>
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-comment"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($comments ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Comments</div>
    </div>
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-chart-line"></i>
        </div>
        <div class="omnipost-stat-number">
            @php
                $total = ($reactions ?? 0) + ($comments ?? 0);
                $engagementRate = $total > 0 ? round(($total / max($reactions, 1)) * 100) : 0;
            @endphp
            {{ $engagementRate }}%
        </div>
        <div class="omnipost-stat-label">Engagement Rate</div>
    </div>
    @else
    <!-- Facebook Stats -->
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-heart"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($reactions ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Reactions</div>
    </div>
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-comment"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($comments ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Comments</div>
    </div>
    <div class="omnipost-stat-card">
        <div class="omnipost-stat-icon-lg">
            <i class="far fa-share-square"></i>
        </div>
        <div class="omnipost-stat-number">{{ number_format($shares ?? 0) }}</div>
        <div class="omnipost-stat-label">Total Shares</div>
    </div>
    @endif
</div>
        
        <div class="omnipost-toggle">
            <button class="omnipost-toggle-btn active" onclick="toggleChart('total')">Total</button>
            <button class="omnipost-toggle-btn" onclick="toggleChart('separated')">Separated</button>
        </div>
        
        <div class="omnipost-chart-card">
            <div class="omnipost-chart-header">
                <span class="omnipost-chart-title">📊 Engagement Rate</span>
                <span class="omnipost-chart-subtitle">Last 7 days</span>
            </div>
            <div class="omnipost-chart-container">
                <canvas id="postAnalyticsChart"></canvas>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentChart = null;

function initChart() {
    const ctx = document.getElementById('postAnalyticsChart').getContext('2d');
    
    let labels = [];
    let engagementData = [];
    let tooltipLabels = [];
    
    @php
        $allPosts = App\Models\Post::where('user_id', (string) auth()->id())
            ->where('status', 'published')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $chartData = [];
        foreach ($allPosts as $p) {
            $totalEngagement = ($reactions ?? 0) + ($comments ?? 0) + ($shares ?? 0);
            $chartData[] = [
                'date' => $p->created_at->format('d M'),
                'time' => $p->created_at->format('h:i A'),
                'engagement' => $totalEngagement,
                'title' => substr(strip_tags($p->content ?? 'Untitled'), 0, 30)
            ];
        }
    @endphp
    
    let postsData = @json($chartData);
    
    postsData.forEach(item => {
        labels.push(item.date);
        engagementData.push(item.engagement);
        tooltipLabels.push(`${item.date} at ${item.time}\nPost: ${item.title}\nEngagement: ${item.engagement}`);
    });
    
    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Engagement',
                data: engagementData,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.05)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return tooltipLabels[context.dataIndex];
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Total Engagement', font: { size: 12 } },
                    grid: { color: '#e9ecef' }
                },
                x: {
                    title: { display: true, text: 'Post Date', font: { size: 12 } },
                    ticks: { 
                        maxRotation: 45,
                        minRotation: 45,
                        autoSkip: true,
                        maxTicksLimit: 8
                    },
                    grid: { display: false }
                }
            }
        }
    });
}

function toggleChart(type) {
    const buttons = document.querySelectorAll('.omnipost-toggle-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (currentChart) {
        let newData = [];
        if (type === 'total') {
            newData = currentChart.data.datasets[0].data.map(d => d);
        } else {
            newData = currentChart.data.datasets[0].data.map(d => Math.floor(d / 2));
        }
        currentChart.data.datasets[0].data = newData;
        currentChart.update();
    }
}

document.addEventListener('DOMContentLoaded', initChart);
</script>

@endsection