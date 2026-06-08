@extends('layouts.index')

@section('title', 'Content Calendar')

@section('content')

@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    $currentDate = Carbon::create($year, $month, 1);

    $startDay = $currentDate->copy()->startOfMonth()->dayOfWeek;

    $daysInMonth = $currentDate->daysInMonth;

    $calendarDays = [];

    for ($i = 0; $i < $startDay; $i++) {
        $calendarDays[] = null;
    }

    for ($i = 1; $i <= $daysInMonth; $i++) {
        $calendarDays[] = $i;
    }

    while (count($calendarDays) % 7 != 0) {
        $calendarDays[] = null;
    }
@endphp

<div class="px-6 py-6">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Content Calendar
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Facebook, Instagram & YouTube content planner
            </p>
        </div>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('calendar.index', [
                    'month' => $currentDate->copy()->subMonth()->month,
                    'year' => $currentDate->copy()->subMonth()->year
                ]) }}"
                class="w-10 h-10 rounded-xl border flex items-center justify-center hover:bg-gray-50 transition"
            >
                ←
            </a>

            <div class="px-5 py-2 rounded-xl border bg-white font-semibold">
                {{ $currentDate->format('F Y') }}
            </div>

            <a
                href="{{ route('calendar.index', [
                    'month' => $currentDate->copy()->addMonth()->month,
                    'year' => $currentDate->copy()->addMonth()->year
                ]) }}"
                class="w-10 h-10 rounded-xl border flex items-center justify-center hover:bg-gray-50 transition"
            >
                →
            </a>

        </div>

    </div>

    <div class="mb-6 flex gap-4 flex-wrap items-center border-b pb-4">
    <div class="mb-6 flex gap-3 flex-wrap">
    <button onclick="filterPlatform('all')" id="filterAll" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold text-sm transition">
        All Platforms
    </button>
    
    <button onclick="filterPlatform('facebook')" id="filterFacebook" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm transition hover:bg-blue-50">
        <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
            <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
        </svg>
        <span>Facebook</span>
    </button>
    
   <button onclick="filterPlatform('instagram')" id="filterInstagram" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm transition hover:bg-pink-50">
    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#E1306C" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.332.014 7.052.072c-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
    </svg>
    <span>Instagram</span>
</button>
    
    <button onclick="filterPlatform('youtube')" id="filterYoutube" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm transition hover:bg-red-50">
        <svg class="w-5 h-5" fill="#FF0000" viewBox="0 0 24 24">
            <path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.6 3.5 12 3.5 12 3.5s-7.6 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12c0 1.9.2 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.8.5 9.4.5 9.4.5s7.6 0 9.4-.5a3 3 0 0 0 2.1-2.1c.3-1.9.5-3.9.5-5.8 0-1.9-.2-3.9-.5-5.8Z"/>
            <polygon fill="white" points="9.5,8 9.5,16 16,12"/>
        </svg>
        <span>YouTube</span>
    </button>
</div>

    <div class="bg-white rounded-3xl border overflow-hidden shadow-sm">

        <div class="grid grid-cols-7 border-b bg-gray-50">

            @foreach($days as $day)

                <div class="px-4 py-4 text-sm font-semibold text-gray-600 border-r last:border-r-0 text-center">
                    {{ $day }}
                </div>

            @endforeach

        </div>

        <div class="grid grid-cols-7">

            @foreach($calendarDays as $day)

                <div class="min-h-[350px] border-r border-b p-3 bg-white">

                    @if($day)

                        @php

                            $dayPosts =
                                $posts->filter(function ($post) use ($day, $month, $year) {

                                    return
                                        Carbon::parse($post->created_at)->day == $day
                                        &&
                                        Carbon::parse($post->created_at)->month == $month
                                        &&
                                        Carbon::parse($post->created_at)->year == $year;

                                });

                        @endphp

                        <div class="flex items-center justify-between mb-3">

                            <span class="font-semibold text-gray-800 text-lg">
                                {{ $day }}
                            </span>

                            @if($dayPosts->count())

                                <span class="text-[11px] bg-blue-100 text-blue-600 px-2 py-1 rounded-full font-semibold">
                                    {{ $dayPosts->count() }}
                                </span>

                            @endif

                        </div>

                        <div class="space-y-3 overflow-y-auto max-h-[280px] custom-scrollbar" id="day-{{ $day }}">

                            @foreach($dayPosts as $post)

                               @php
                                $platformsList = $post->platforms ?? [];
                                $platform = !empty($platformsList) ? implode(' + ', array_map('ucfirst', $platformsList)) : 'Social';
                                $displayPlatform = $platformsList[0] ?? 'social'; 
                                
                                $typeLabels = [];
                                if (in_array('facebook', $platformsList) && $post->facebook_post_type) {
                                    $typeLabels[] = ucfirst($post->facebook_post_type);
                                }
                                if (in_array('instagram', $platformsList) && $post->ig_post_type) {
                                    $typeLabels[] = ucfirst($post->ig_post_type);
                                }
                                if (in_array('youtube', $platformsList)) {
                                    $typeLabels[] = 'Video';
                                }
                                $typeLabel = !empty($typeLabels) ? implode(' + ', $typeLabels) : 'Post';
                                    
                                    $media = null;
                                    $mediaCount = 0;

                                    $youtubeId = null;
                                    $isVideo = false;
                                    $isImage = false;
                                    $isYouTube = false;
                                    $youtubeUrl = null;

                                    if (!empty($post->media_paths) && is_array($post->media_paths)) {

                                        $mediaCount = count($post->media_paths);

                                        $media = asset(
                                            'storage/' . $post->media_paths[0]
                                        );

                                    }
                                    elseif (!empty($post->media_url)) {

                                        $media = $post->media_url;

                                    }
                                    elseif (!empty($post->media_path)) {

                                        $media = asset(
                                            'storage/' . $post->media_path
                                        );

                                        $mediaCount = 1;
                                    }
                                    
                                    // YouTube check
                                    if (in_array('youtube', $platformsList)) {
                                        $youtubePattern = '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/';
                                        if (!empty($post->media_path) && preg_match($youtubePattern, $post->media_path, $matches)) {
                                            $youtubeId = $matches[1];
                                            $isYouTube = true;
                                            $youtubeUrl = "https://www.youtube.com/watch?v={$youtubeId}";
                                            $media = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
                                        } elseif (!empty($post->media_url) && preg_match($youtubePattern, $post->media_url, $matches)) {
                                            $youtubeId = $matches[1];
                                            $isYouTube = true;
                                            $youtubeUrl = "https://www.youtube.com/watch?v={$youtubeId}";
                                            $media = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
                                        }
                                    }
                                    
                                    if ($media && !$isYouTube) {
                                        $mediaLower = strtolower($media);
                                        if (preg_match('/\.(mp4|mov|avi|webm|mkv|m4v)$/i', $mediaLower)) {
                                            $isVideo = true;
                                        } elseif (preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $mediaLower)) {
                                            $isImage = true;
                                        }
                                    }
                                    
                                   $bgColor = match($displayPlatform) {
                                        'facebook' => 'border-l-4 border-l-blue-500 bg-blue-50 hover:bg-blue-100',
                                        'instagram' => 'border-l-4 border-l-pink-500 bg-pink-50 hover:bg-pink-100',
                                        'youtube' => 'border-l-4 border-l-red-600 bg-red-50 hover:bg-red-100',
                                        default => 'border-l-4 border-l-gray-500 bg-gray-50 hover:bg-gray-100'
                                    };

                                    $platformLabel = $platform;  
                                    $platformClass = $displayPlatform;  
                                @endphp

                                <div 
                                    class="post-card {{ $platformClass }} {{ $bgColor }} border rounded-xl p-3 cursor-pointer hover:shadow-lg transition-all duration-200"
                                    data-platform="{{ implode(' ', $platformsList) }}"
                                    onclick='openMobilePreview(
                                        {{ json_encode($platformLabel) }},
                                        {{ json_encode($typeLabel) }},
                                        {{ json_encode($post->formatted_content ?? $post->content) }},
                                        {{ json_encode($media) }},
                                        {{ json_encode($isVideo) }},
                                        {{ json_encode($isImage) }},
                                        {{ json_encode($isYouTube) }},
                                        {{ json_encode($youtubeId) }},
                                        {{ json_encode($youtubeUrl) }},
                                        {{ json_encode($post->media_paths ?? []) }}
                                    )'
                                >
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex items-center gap-1">
                                            @if(in_array('facebook', $platformsList))
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#1877F2">
                                                    <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
                                                </svg>
                                            @endif
                                           @if(in_array('instagram', $platformsList))
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#E1306C">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.332.014 7.052.072c-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                                                </svg>
                                            @endif
                                            @if(in_array('youtube', $platformsList))
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#FF0000">
                                                    <path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.6 3.5 12 3.5 12 3.5s-7.6 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12c0 1.9.2 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.8.5 9.4.5 9.4.5s7.6 0 9.4-.5a3 3 0 0 0 2.1-2.1c.3-1.9.5-3.9.5-5.8 0-1.9-.2-3.9-.5-5.8Z"/>
                                                    <polygon fill="white" points="9.5,8 9.5,16 16,12"/>
                                                </svg>
                                            @endif
                                        </div>


                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-800">{{ $platformLabel }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $typeLabel }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($media && !$isYouTube)
                                        <div class="rounded-lg overflow-hidden bg-black mb-2 h-32">
                                            @if($isVideo)
                                                <video class="w-full h-full object-cover" muted playsinline preload="metadata">
                                                    <source src="{{ $media }}">
                                                </video>
                                            @elseif($isImage)
                                                <img src="{{ $media }}" class="w-full h-full object-cover" loading="lazy">
                                            @endif
                                        </div>
                                    @elseif($isYouTube && $youtubeId)
                                        <div class="rounded-lg overflow-hidden bg-gray-100 mb-2 h-32 relative">
                                            <img src="{{ $media }}" class="w-full h-full object-cover" loading="lazy">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    @if($mediaCount > 1)
                                        <div class="mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-600 text-[10px] font-semibold">
                                                📷 {{ $mediaCount }} Photos

                                            </span>

                                        </div>

                                    @endif
                                    @if($post->content)
                                        <p class="text-xs text-gray-700 leading-relaxed line-clamp-2">
                                            {{ Str::limit($post->formatted_content ?? $post->content, 80) }}
                                        </p>
                                    @endif
                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    </div>

</div>

<!-- Mobile Style Preview Modal - Like Publer -->
<div id="previewModal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl" style="max-width: 380px;">
        
        <!-- Header -->
        <div class="p-4 border-b flex items-center justify-between bg-white">
            <div>
                <h3 class="font-semibold text-gray-900" id="previewTitle">Post Preview</h3>
                <p class="text-xs text-gray-500" id="previewSubtitle"></p>
            </div>
            <button onclick="closePreview()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 text-xl">
                ×
            </button>
        </div>
        
        <!-- Video Container - Like Mobile Screen -->
        <div id="videoContainer" class="bg-black relative" style="min-height: 500px;">
            <div id="videoContent" class="flex items-center justify-center" style="min-height: 500px;">
                <!-- Dynamic content -->
            </div>
        </div>
        
        <!-- Content Footer -->
        <div id="contentFooter" class="p-4 bg-white border-t">
            <!-- Dynamic content -->
        </div>
        
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.h-32 {
    height: 8rem;
}
.object-cover {
    object-fit: cover;
}
.post-card {
    transition: all 0.3s ease;
}
</style>

<script>

let currentFilter = 'all';

function filterPlatform(platform) {
    currentFilter = platform;
    
    const buttons = ['all', 'facebook', 'instagram', 'youtube'];
    buttons.forEach(btn => {
        const element = document.getElementById(`filter${btn.charAt(0).toUpperCase() + btn.slice(1)}`);
        if (element) {
            if (btn === platform) {
                if (btn === 'all') element.className = 'px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold text-sm transition';
                else if (btn === 'facebook') element.className = 'px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold text-sm transition';
                else if (btn === 'instagram') element.className = 'px-4 py-2 rounded-xl bg-pink-600 text-white font-semibold text-sm transition';
                else if (btn === 'youtube') element.className = 'px-4 py-2 rounded-xl bg-red-600 text-white font-semibold text-sm transition';
            } else {
                element.className = 'px-4 py-2 rounded-xl bg-gray-200 text-gray-700 font-semibold text-sm transition hover:bg-gray-300';
            }
        }
    });
    
    const allPosts = document.querySelectorAll('.post-card');
        allPosts.forEach(post => {
            if (platform === 'all') {
                post.style.display = 'block';
            } else {
                const postPlatforms = post.getAttribute('data-platform').split(' ');
                if (postPlatforms.includes(platform)) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            }
        });
    
    for (let day = 1; day <= 31; day++) {
        const dayContainer = document.getElementById(`day-${day}`);
        if (dayContainer) {
            const visiblePosts = dayContainer.querySelectorAll('.post-card[style="display: block"]');
            const count = visiblePosts.length;
            const countBadge = dayContainer.parentElement.querySelector('.bg-blue-100');
            if (countBadge) {
                if (count > 0) {
                    countBadge.textContent = count;
                    countBadge.style.display = 'inline-block';
                } else countBadge.style.display = 'none';
            }
        }
    }
}

function openMobilePreview(platform, type, content, media, isVideo, isImage, isYouTube, youtubeId, youtubeUrl, mediaPaths = []) {
    console.log('Preview:', {platform, type, media, isVideo, isYouTube});
    
    const modal = document.getElementById('previewModal');
    const videoContent = document.getElementById('videoContent');
    const contentFooter = document.getElementById('contentFooter');
    const previewTitle = document.getElementById('previewTitle');
    const previewSubtitle = document.getElementById('previewSubtitle');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    previewTitle.innerHTML = platform;
    previewSubtitle.innerHTML = type;
    
    // YOUTUBE
    if (isYouTube && youtubeId) {
        videoContent.innerHTML = `
            <div class="relative w-full" style="padding-bottom: 177.77%;">
                <iframe 
                    class="absolute top-0 left-0 w-full h-full"
                    src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        `;
        contentFooter.innerHTML = content && content !== 'null' ? `
            <p class="text-gray-700 text-sm leading-relaxed">${escapeHtml(content)}</p>
        ` : `<p class="text-gray-400 text-sm">No content</p>`;
    }
    // VIDEO
    else if (isVideo && media) {
        videoContent.innerHTML = `
            <video 
                id="mobileVideo"
                controls
                autoplay
                playsinline
                class="w-full h-full object-contain"
                style="max-height: 500px;"
            >
                <source src="${media}" type="video/mp4">
            </video>
        `;
        contentFooter.innerHTML = content && content !== 'null' ? `
            <p class="text-gray-700 text-sm leading-relaxed">${escapeHtml(content)}</p>
        ` : `<p class="text-gray-400 text-sm">No content</p>`;
        
        setTimeout(() => {
            const video = document.getElementById('mobileVideo');
            if (video) {
                video.addEventListener('error', function() {
                    videoContent.innerHTML = `
                        <div class="text-center p-8">
                            <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-white text-sm">Cannot play video</p>
                            <a href="${media}" target="_blank" class="text-blue-400 text-xs underline mt-2 inline-block">Open directly</a>
                        </div>
                    `;
                });
            }
        }, 100);
    }
   else if (isImage && media) {

    if (mediaPaths.length > 1) {

        window.calendarImages = mediaPaths.map(
            path => '/storage/' + path
        );

        window.calendarCurrentImage = 0;

        videoContent.innerHTML = `
            <div class="relative w-full">

                <img
                    id="calendarCarouselImage"
                    src="${window.calendarImages[0]}"
                    class="w-full object-contain"
                    style="max-height:500px;"
                >

                <button
                    onclick="calendarPrevImage()"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/60 text-white px-3 py-2 rounded-full"
                >
                    ‹
                </button>

                <button
                    onclick="calendarNextImage()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/60 text-white px-3 py-2 rounded-full"
                >
                    ›
                </button>

                <div
                    id="calendarCounter"
                    class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/60 text-white px-3 py-1 rounded-full text-xs"
                >
                    1 / ${window.calendarImages.length}
                </div>

            </div>
        `;

    } else {

        videoContent.innerHTML = `
            <img
                src="${media}"
                class="w-full object-contain"
                style="max-height:500px;"
            >
        `;
    }

    contentFooter.innerHTML = content && content !== 'null'
        ? `<p class="text-gray-700 text-sm leading-relaxed">${escapeHtml(content)}</p>`
        : `<p class="text-gray-400 text-sm">No content</p>`;
}
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    }).replace(/\n/g, '<br>');
}

function closePreview() {
    const modal = document.getElementById('previewModal');
    const videoContent = document.getElementById('videoContent');
    
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    if (videoContent) videoContent.innerHTML = '';
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') closePreview();
});
function calendarNextImage() {

    window.calendarCurrentImage++;

    if (
        window.calendarCurrentImage >=
        window.calendarImages.length
    ) {
        window.calendarCurrentImage = 0;
    }

    document.getElementById(
        'calendarCarouselImage'
    ).src =
        window.calendarImages[
            window.calendarCurrentImage
        ];

    document.getElementById(
        'calendarCounter'
    ).innerText =
        `${window.calendarCurrentImage + 1} / ${window.calendarImages.length}`;
}

function calendarPrevImage() {

    window.calendarCurrentImage--;

    if (
        window.calendarCurrentImage < 0
    ) {
        window.calendarCurrentImage =
            window.calendarImages.length - 1;
    }

    document.getElementById(
        'calendarCarouselImage'
    ).src =
        window.calendarImages[
            window.calendarCurrentImage
        ];

    document.getElementById(
        'calendarCounter'
    ).innerText =
        `${window.calendarCurrentImage + 1} / ${window.calendarImages.length}`;
}
</script>

@endsection