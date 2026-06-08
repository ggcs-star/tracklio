@extends('layouts.index')

@section('title', 'Create Post')

@section('content')

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
@csrf

{{-- 🔴 ERROR MESSAGE --}}
@if ($errors->has('publish_error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 animate-fadeIn">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold">Post Failed</h3>
                <p class="text-sm mt-0.5">{{ $errors->first('publish_error') }}</p>
            </div>
        </div>
    </div>
@endif

{{-- 🟢 SUCCESS MESSAGE --}}
@if (session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 animate-fadeIn">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold">Success</h3>
                <p class="text-sm mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="flex flex-col lg:flex-row gap-8 min-h-[calc(100vh-80px)]">

{{-- ================= LEFT ================= --}}
<div class="w-full lg:w-[70%] space-y-8">

    {{-- HEADER --}}
    <div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create New Post</h2>
        <p class="text-gray-600">Craft your content and publish to connected platforms</p>
    </div>

    {{-- PUBLISH BAR --}}
    <div class="bg-gradient-to-r from-white to-blue-50 rounded-2xl border border-blue-100 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-medium text-gray-700 mb-2">Post Status</label>
                <select name="status" class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    <option value="Draft">Save as Draft</option>
                    <option value="Published" selected>Publish Now</option>
                    <option value="scheduled">Schedule Post </option>
                    
                </select>
                <div id="scheduleBox" class="hidden mt-4">
                    <input
                        type="datetime-local"
                        name="scheduled_at"
                        class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                    >
                </div>
            </div>
            
            <button type="submit"
                class="group relative inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 min-w-[160px]">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Publish Post
            </button>
        </div>
    </div>

    {{-- PLATFORMS SECTION --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Platforms</h3>
        <p class="text-sm text-gray-500 mb-6">Choose where you want to publish this post</p>

        @php
            $platformMap = [
                'facebook'  => [
                    'label' => 'Facebook',
                    'icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
                    'color' => 'bg-blue-100 text-blue-600'
                ],
                'instagram' => [
                    'label' => 'Instagram',
                    'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.332.014 7.052.072c-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z',
                    'color' => 'bg-pink-100 text-pink-600'
                ],
                'youtube'   => [
                    'label' => 'YouTube',
                    'icon' => 'M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z',
                    'color' => 'bg-red-100 text-red-600'
                ],
            ];
        @endphp

        <input type="hidden" name="platforms" id="selectedPlatformsInput">
        <!-- <input type="hidden" name="content" id="contentInput"> -->
         <input type="hidden" name="location_id" id="locationIdInput">
         <input type="hidden" name="location_name" id="locationNameInput">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach ($platformMap as $key => $platform)
                @if($accounts->has($key))
                    <button type="button"
                        class="platform-btn group relative border-2 border-gray-200 rounded-2xl p-5 text-center transition-all duration-300 hover:border-blue-300 hover:shadow-md"
                        data-key="{{ $key }}"
                        data-label="{{ $platform['label'] }}">
                        <div class="flex flex-col items-center gap-3">
                            <div class="{{ $platform['color'] }} rounded-xl p-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="{{ $platform['icon'] }}"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700">{{ $platform['label'] }}</span>
                            <div class="h-6 w-6 rounded-full border-2 border-gray-300 group-hover:border-blue-500 transition-colors flex items-center justify-center">
                                <div class="check-icon hidden w-3 h-3 rounded-full bg-blue-500"></div>
                            </div>
                        </div>
                    </button>
                @endif
            @endforeach
        </div>
    </div>

   {{-- FACEBOOK PAGE --}}
@if(!empty($facebookPages))
<div id="facebookPageBox" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hidden animate-slideDown">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-blue-100 rounded-lg p-2">
            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900">Facebook Page</h3>
            <p class="text-sm text-gray-500">Select profile, then select page</p>
        </div>
    </div>
    
    <!-- Profile Selector -->
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Select Facebook Profile</label>
    <select id="facebookProfileSelect" class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
        <option value="">-- Select Profile --</option>
        @if(isset($facebookGroupedPages) && !empty($facebookGroupedPages))
            @foreach($facebookGroupedPages as $profileId => $group)
                <option value="{{ $profileId }}">{{ $group['profile_name'] }}</option>
            @endforeach
        @endif
    </select>
</div>
    
    <!-- Page Selector -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Select Facebook Page</label>
        <select name="facebook_page_id" id="facebookPageSelect"
            class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
            <option value="">-- Select a Page --</option>
        </select>
    </div>
    
    <!-- Facebook Post Type -->
    <div class="mt-4">
        <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700 mb-3">Facebook Post Type</label>
            <div class="flex gap-3 flex-wrap">
                <label class="flex items-center gap-2 px-4 py-2 border rounded-xl cursor-pointer hover:bg-blue-50">
                    <input type="radio" name="facebook_post_type" value="post" checked>
                    <span>📝 Post</span>
                </label>
                <label class="flex items-center gap-2 px-4 py-2 border rounded-xl cursor-pointer hover:bg-blue-50">
                    <input type="radio" name="facebook_post_type" value="reel">
                    <span>🎬 Reel</span>
                </label>
                <label class="flex items-center gap-2 px-4 py-2 border rounded-xl cursor-pointer hover:bg-blue-50">
                    <input type="radio" name="facebook_post_type" value="story">
                    <span>📸 Story</span>
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-2" id="fbPostTypeHint">Post: Regular Facebook feed post</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileSelect = document.getElementById('facebookProfileSelect');
    const pageSelect = document.getElementById('facebookPageSelect');
    
    if (profileSelect && pageSelect) {
        const groupedPages = @json($facebookGroupedPages ?? []);
        
        profileSelect.addEventListener('change', function() {
            const selectedProfileId = this.value;
            pageSelect.innerHTML = '<option value="">-- Select a Page --</option>';
            
            if (selectedProfileId && groupedPages[selectedProfileId]) {
                groupedPages[selectedProfileId].pages.forEach(function(page) {
                    const option = document.createElement('option');
                    option.value = page.page_id;
                    option.textContent = page.page_name || 'Facebook Page';
                    pageSelect.appendChild(option);
                });
                updateFacebookPreview();
            }
        });
        
        pageSelect.addEventListener('change', updateFacebookPreview);
    }
});
</script>
@endif

{{-- INSTAGRAM SECTION --}}
<div id="instagramUrlBox" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hidden animate-slideDown">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg p-2">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/>
            </svg>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900">Instagram Settings</h3>
            <p class="text-sm text-gray-500">Select profile, then choose post type</p>
        </div>
    </div>
    
    @if(isset($instagramProfiles) && $instagramProfiles->count() > 0)
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Select Instagram Profile</label>
        <select name="instagram_profile_id" id="instagramProfileSelect"
            class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-pink-500 focus:ring-pink-500 transition-colors">
            <option value="">-- Select Profile --</option>
            @foreach($instagramProfiles as $profile)
                <option value="{{ $profile->id }}">{{ $profile->credentials['username'] ?? 'Instagram Account' }}</option>
            @endforeach
        </select>
    </div>
    @endif
    
    <div class="mb-2">
        <label class="block text-sm font-medium text-gray-700 mb-3">Post Type</label>
        <div class="flex gap-3 flex-wrap">
            <label class="flex items-center gap-2 px-4 py-2 border rounded-xl cursor-pointer hover:bg-pink-50">
                <input type="radio" name="ig_post_type" value="post" checked>
                <span>📷 Post (Image)</span>
            </label>
            <label class="flex items-center gap-2 px-4 py-2 border rounded-xl cursor-pointer hover:bg-pink-50">
                <input type="radio" name="ig_post_type" value="story">
                <span>📸 Story</span>
            </label>
            <label class="flex items-center gap-2 px-4 py-2 border rounded-xl cursor-pointer hover:bg-pink-50">
                <input type="radio" name="ig_post_type" value="reel">
                <span>🎬 Reel</span>
            </label>
        </div>
        <p class="text-xs text-gray-500 mt-2" id="igPostTypeHint">Post: Static image only (JPG/PNG)</p>
    </div>
</div>
<div id="youtubeBox"
    class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hidden animate-slideDown">

    <div class="flex items-center gap-3 mb-4">
        <div class="bg-red-100 rounded-lg p-2">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
            </svg>
        </div>

        <div>
            <h3 class="font-semibold text-gray-900">
                YouTube Channel
            </h3>
            <p class="text-sm text-gray-500">
                Select YouTube Channel
            </p>
        </div>
    </div>

    <select
        name="youtube_account_id"
        id="youtubeAccountSelect"
        class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm">

        <option value="">
            -- Select Channel --
        </option>

        @foreach($youtubeAccounts as $account)
            <option value="{{ $account->id }}">
                {{ $account->credentials['channel_name'] ?? 'YouTube Channel' }}
            </option>
        @endforeach

    </select>
</div>
    {{-- CONTENT --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-900">Post Content</h3>
                <p class="text-sm text-gray-500">Write your post content</p>
            </div>
            <div class="text-sm font-medium text-gray-500" id="charCount">0/280</div>
        </div>
           <!-- <button type="button" onclick="document.execCommand('underline',false,null)" class="p-2 rounded hover:bg-gray-200"><u>U</u></button> -->
    <div class="flex gap-1 mb-3 p-2 bg-gray-50 rounded-xl border">
    <button type="button" onclick="document.execCommand('bold',false,null)" class="p-2 rounded hover:bg-gray-200"><b>B</b></button>
    <button type="button" onclick="document.execCommand('italic',false,null)" class="p-2 rounded hover:bg-gray-200"><i>I</i></button>
     <button type="button" id="locationBtn" class="p-2 rounded hover:bg-gray-200" title="Add Location">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </button>
    <button type="button" id="hashtagBtn" class="p-2 rounded hover:bg-gray-200" title="Add Hashtag">
        <span class="font-bold text-lg">#</span>
    </button>
    <div id="emojiButtonContainer" style="position: relative; display: inline-block;">
        <button type="button" id="emojiBtn" class="p-2 rounded hover:bg-gray-200">😊</button>
    </div>
</div>

        
        <div id="postText" contenteditable="true"
        class="w-full p-5 border border-gray-300 rounded-xl min-h-[180px] text-gray-900 focus:border-blue-500 focus:ring-blue-500 resize-none overflow-auto"
        style="max-height: 300px; line-height: 1.6;"></div>
    <input type="hidden" name="content" id="contentInput">
    <div id="selectedLocationDisplay" class="hidden mt-3"></div>
    </div>

    {{-- MEDIA UPLOAD --}}
{{-- MEDIA UPLOAD - PUBLER STYLE --}}
<div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-semibold text-gray-900">Media Attachment</h3>
            <p class="text-sm text-gray-500 mt-1">Upload up to 10 images</p>
        </div>
        <div class="text-xs text-gray-400" id="mediaCount">0/10</div>
    </div>
    
    <!-- Dropzone -->
    <div id="dropzone" 
         class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center cursor-pointer transition-all duration-300 hover:border-blue-400 hover:bg-blue-50/30 mb-4"
         onclick="document.getElementById('imageInput').click()">
        
        <input type="file" name="media_files[]" id="imageInput" accept="image/*,video/*" multiple hidden>
        
        <div class="flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div>
                <p class="font-medium text-gray-900">Drag & drop images here</p>
                <p class="text-sm text-gray-500 mt-1">or click to select (JPG, PNG, MP4)</p>
            </div>
            <button type="button" 
                    onclick="event.stopPropagation(); document.getElementById('imageInput').click()" 
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Choose Files
            </button>
        </div>
    </div>
    
    <!-- Preview Grid -->
<div id="mediaPreviewGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-4"></div>

<!-- Add More Button -->
<div id="addMoreBtn" class="hidden mt-3">
    <button type="button" 
            onclick="document.getElementById('imageInput').click()" 
            class="w-full py-2 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-blue-400 hover:text-blue-500 transition-colors flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add More Images
    </button>
</div>

<!-- Hidden - keep for compatibility -->
<div id="uploadedPreview" class="hidden"></div>
</div>

<!-- Hidden single image preview (keep for compatibility) -->
<!-- <div id="uploadedPreview" class="hidden"></div> -->
</div>
<div id="locationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-[500px] max-w-[90%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Add Location</h3>
            <button type="button" onclick="closeLocationModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="p-4 border-b">
            <input type="text" id="locationSearchInput" placeholder="Search for a location..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 outline-none">
        </div>
        <div id="locationResults" class="max-h-[400px] overflow-y-auto">
            <div class="p-4 text-center text-gray-500">Type to search locations</div>
        </div>
    </div>
</div>

<div id="hashtagModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-[500px] max-w-[90%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Add Hashtags</h3>
            <button type="button" onclick="closeHashtagModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="p-4 border-b">
            <input type="text" id="hashtagSearchInput" placeholder="Search hashtags..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 outline-none">
        </div>
        <div id="hashtagResults" class="max-h-[400px] overflow-y-auto">
            <div class="p-4 text-center text-gray-500">Type to search hashtags</div>
        </div>
    </div>
</div>
{{-- ================= RIGHT PREVIEW ================= --}}
<div class="w-full lg:w-[30%] lg:sticky lg:top-6 lg:self-start">
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200 p-6 shadow-sm sticky top-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-blue-100 rounded-lg p-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Preview</h3>
                <p class="text-sm text-gray-500">How your post will appear</p>
            </div>
        </div>

        <div id="previewContainer" class="space-y-4 max-h-[calc(100vh-250px)] overflow-y-auto pr-2">
            <!-- Preview cards will be inserted here -->
        </div>

        <div id="emptyPreview" class="text-center py-12">
            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">No platforms selected</p>
            <p class="text-sm text-gray-400 mt-1">Select platforms to see preview</p>
        </div>
    </div>
</div>

</div>
</form>
@endsection

@push('styles')
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-slideDown {
    animation: slideDown 0.3s ease-out;
}

.platform-btn.active {
    border-color: #4C6FFF;
    background: linear-gradient(to bottom right, #f8faff, #eef2ff);
    box-shadow: 0 4px 12px rgba(76, 111, 255, 0.1);
}

.platform-btn.active .check-icon {
    display: block;
}

.preview-card {
    background: white;
    border-radius: 16px;
    padding: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
}

.preview-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.preview-img {
    width: 100%;
    border-radius: 12px;
    margin-top: 12px;
    object-fit: cover;
    max-height: 200px;
}

.facebook-post-preview {
    background: white;
    border-radius: 12px;
    border: 1px solid #e4e6eb;
    overflow: hidden;
}

.facebook-post-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
}

.facebook-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1877F2, #0c63d4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.facebook-post-name {
    font-weight: 600;
    color: #050505;
    font-size: 15px;
}

.facebook-post-time {
    font-size: 12px;
    color: #65676b;
}

.facebook-post-content {
    padding: 0 16px 12px 16px;
    font-size: 15px;
    line-height: 1.38;
    color: #050505;
}

.facebook-media-grid {
    display: grid;
    gap: 2px;
    margin-top: 8px;
}

.facebook-grid-1 {
    grid-template-columns: 1fr;
}

.facebook-grid-2 {
    grid-template-columns: 1fr 1fr;
}

.facebook-grid-3, .facebook-grid-4 {
    grid-template-columns: repeat(2, 1fr);
}

.facebook-media-item {
    position: relative;
    background: #f0f2f5;
    min-height: 200px;
    overflow: hidden;
}

.facebook-media-item img, .facebook-media-item video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.facebook-actions {
    display: flex;
    justify-content: space-around;
    padding: 8px 16px;
    border-top: 1px solid #e4e6eb;
    margin-top: 8px;
}

.facebook-action-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #65676b;
    font-weight: 500;
    font-size: 13px;
    cursor: default;
}

.instagram-post-preview {
    background: white;
    border-radius: 16px;
    border: 1px solid #dbdbdb;
    overflow: hidden;
}

.instagram-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
}

.instagram-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.instagram-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fccc63, #e2346e, #ac2b9e);
    display: flex;
    align-items: center;
    justify-content: center;
}

.instagram-username {
    font-weight: 600;
    font-size: 14px;
    color: #262626;
}

.instagram-caption {
    padding: 12px 16px;
    font-size: 14px;
    line-height: 1.4;
}

.instagram-actions {
    display: flex;
    gap: 16px;
    padding: 8px 16px;
    border-top: 1px solid #efefef;
}

.youtube-preview {
    background: #0f0f0f;
    border-radius: 12px;
    overflow: hidden;
    color: white;
}

.youtube-thumbnail {
    position: relative;
    background: #1a1a1a;
    aspect-ratio: 16/9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.youtube-play-icon {
    position: absolute;
    width: 48px;
    height: 48px;
    background: rgba(0,0,0,0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.youtube-info {
    padding: 12px 16px;
}

.youtube-channel {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.youtube-channel-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ff0000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.youtube-channel-name {
    font-weight: 500;
    font-size: 14px;
    color: #f1f1f1;
}

.youtube-title {
    font-weight: 500;
    font-size: 16px;
    margin-bottom: 8px;
}

.youtube-description {
    font-size: 12px;
    color: #aaaaaa;
}

.facebook-reel-preview, .instagram-reel-preview {
    background: #000;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    aspect-ratio: 9/16;
    max-height: 500px;
}

.reel-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
}

.reel-username {
    color: white;
    font-weight: 600;
    margin-bottom: 8px;
}

.reel-caption {
    color: white;
    font-size: 13px;
}

.reel-actions {
    position: absolute;
    right: 12px;
    bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.reel-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    color: white;
    font-size: 12px;
}

.facebook-story-preview, .instagram-story-preview {
    background: #1a1a1a;
    border-radius: 20px;
    overflow: hidden;
    aspect-ratio: 9/16;
    max-height: 500px;
    position: relative;
}

.story-progress-bar {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    height: 3px;
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
    overflow: hidden;
}

.story-progress {
    width: 100%;
    height: 100%;
    background: white;
    border-radius: 3px;
    animation: storyProgress 5s linear;
}

@keyframes storyProgress {
    from { width: 0%; }
    to { width: 100%; }
}

.story-header {
    position: absolute;
    top: 20px;
    left: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.story-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #1877F2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.story-name {
    color: white;
    font-weight: 600;
}

/* Custom scrollbar for preview */
#previewContainer::-webkit-scrollbar {
    width: 6px;
}

#previewContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#previewContainer::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#previewContainer::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
#emojiButtonContainer {
    position: relative;
    display: inline-block;
}

.emoji-mart {
    position: fixed !important;
    width: 280px !important;
    height: 350px !important;
    background: white !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
    z-index: 100000 !important;
    display: none !important;
}

.emoji-mart .emoji-mart-scroll {
    height: 260px !important;
    overflow-y: scroll !important;
}

.emoji-mart .emoji-mart-search {
    padding: 8px !important;
}

.emoji-mart .emoji-mart-search input {
    font-size: 13px !important;
    padding: 8px !important;
    border: 1px solid #ddd !important;
    border-radius: 8px !important;
}

.emoji-mart .emoji-mart-emoji {
    width: 32px !important;
    height: 32px !important;
}

.emoji-mart .emoji-mart-emoji span {
    font-size: 22px !important;
}
.location-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f0f2f5;
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 13px;
    margin-top: 12px;
}

.suggestion-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #e4e6eb;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    min-width: 280px;
}

.suggestion-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.2s;
}

.suggestion-item:hover,
.suggestion-item.active {
    background: #f0f2f5;
}

.suggestion-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1877F2, #0c63d4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.suggestion-info {
    flex: 1;
}

.suggestion-name {
    font-weight: 600;
    font-size: 14px;
    color: #050505;
}

.suggestion-meta {
    font-size: 12px;
    color: #65676b;
}

.suggestion-checkins {
    font-size: 11px;
    color: #65676b;
    margin-top: 2px;
}

.hashtag-count {
    font-size: 11px;
    color: #65676b;
    margin-left: 8px;
}

.location-icon-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    color: #65676b;
    cursor: pointer;
    padding: 6px 10px;
    border-radius: 20px;
    transition: background 0.2s;
}

.location-icon-btn:hover {
    background: #f0f2f5;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.location-modal {
    background: white;
    border-radius: 16px;
    width: 500px;
    max-width: 90%;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(0, 0, 0, 0.28);
}

.location-modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e4e6eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.location-modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.location-modal-search {
    padding: 16px 20px;
    border-bottom: 1px solid #e4e6eb;
}

.location-modal-search input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e4e6eb;
    border-radius: 24px;
    font-size: 15px;
    outline: none;
}

.location-modal-search input:focus {
    border-color: #1877F2;
}

.location-results {
    max-height: 400px;
    overflow-y: auto;
}

.location-result-item {
    padding: 12px 20px;
    cursor: pointer;
    transition: background 0.2s;
}

.location-result-item:hover {
    background: #f0f2f5;
}

.location-result-name {
    font-weight: 600;
    font-size: 15px;
    color: #050505;
}

.location-result-address {
    font-size: 13px;
    color: #65676b;
    margin-top: 2px;
}

.remove-location {
    margin-left: 8px;
    cursor: pointer;
    color: #65676b;
}

.remove-location:hover {
    color: #e41e3f;
}
.facebook-post-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 16px;
}

.facebook-post-name {
    font-weight: 600;
    color: #050505;
    font-size: 15px;
    margin-bottom: 2px;
}

.facebook-post-time {
    font-size: 12px;
    color: #65676b;
    display: flex;
    align-items: center;
    gap: 4px;
}

.facebook-post-header a {
    text-decoration: none;
}

.facebook-post-header a:hover {
    text-decoration: underline;
}
</style>
@endpush

@push('scripts')
<script>
let selected = [];
let selectedFiles = [];
let filePreviews = [];
let selectedFacebookPage = null;
let selectedInstagramProfile = null;
let selectedYoutubeChannel = null;
let facebookPostType = 'post';
let instagramPostType = 'post';
function renderPreview() {
    const preview = document.getElementById('previewContainer');
    const emptyPreview = document.getElementById('emptyPreview');
    const postText = document.getElementById('postText');
    if (!preview) return;
    preview.innerHTML = '';
    
    if (!selected.length) {
        if (emptyPreview) emptyPreview.style.display = 'block';
        return;
    }
    
    if (emptyPreview) emptyPreview.style.display = 'none';
    
    const content = postText ? postText.innerText || '' : '';
    const mediaFiles = selectedFiles;
    
    selected.forEach(platform => {
        let html = '';
        
        if (platform === 'facebook') {
            if (facebookPostType === 'reel') {
                html = renderFacebookReelPreview(content, mediaFiles);
            } else if (facebookPostType === 'story') {
                html = renderFacebookStoryPreview(mediaFiles);
            } else {
                html = renderFacebookPostPreview(content, mediaFiles);
            }
        } else if (platform === 'instagram') {
            if (instagramPostType === 'reel') {
                html = renderInstagramReelPreview(content, mediaFiles);
            } else if (instagramPostType === 'story') {
                html = renderInstagramStoryPreview(mediaFiles);
            } else {
                html = renderInstagramPostPreview(content, mediaFiles);
            }
        } else if (platform === 'youtube') {
            html = renderYouTubePreview(content, mediaFiles);
        }
        
        if (html) {
            const wrapper = document.createElement('div');
            wrapper.className = 'preview-card';
            wrapper.style.padding = '0';
            wrapper.style.overflow = 'hidden';
            wrapper.innerHTML = html;
            preview.appendChild(wrapper);
        }
    });
}
function updateFacebookPreview() {
    const pageSelect = document.getElementById('facebookPageSelect');
    if (pageSelect && pageSelect.value) {
        const selectedOption = pageSelect.options[pageSelect.selectedIndex];
        selectedFacebookPage = {
            id: pageSelect.value,
            name: selectedOption.textContent
        };
    } else {
        selectedFacebookPage = null;
    }
    renderPreview();
}
function renderFacebookPostPreview(content, mediaFiles) {
    const pageName = selectedFacebookPage ? selectedFacebookPage.name : 'Facebook Page';
    const pageInitial = pageName.charAt(0).toUpperCase();
    const timeAgo = 'Just now';
    const mediaCount = mediaFiles.length;
    
    let locationHtml = '';
    if (selectedLocation && selectedLocation.name) {
        const facebookLocationUrl = `https://www.facebook.com/search/top?q=${encodeURIComponent(selectedLocation.name)}`;
        locationHtml = `
            <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#65676b" stroke-width="2">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <a href="${facebookLocationUrl}" target="_blank" style="color: #65676b; font-size: 13px; text-decoration: none;">
                    is at ${escapeHtml(selectedLocation.name)}
                </a>
            </div>
        `;
    }
    
    let mediaHtml = '';
    
    if (mediaCount > 0) {
        let gridClass = 'facebook-grid-1';
        if (mediaCount === 2) gridClass = 'facebook-grid-2';
        if (mediaCount >= 3) gridClass = 'facebook-grid-3';
        
        mediaHtml = `<div class="facebook-media-grid ${gridClass}" style="margin-top: 8px;">`;
        
        const displayCount = Math.min(mediaCount, 4);
        for (let i = 0; i < displayCount; i++) {
            const file = mediaFiles[i];
            const isVideo = file && file.type && file.type.startsWith('video/');
            mediaHtml += `
                <div class="facebook-media-item" style="aspect-ratio: ${isVideo ? '9/16' : '1/1'};">
                    ${isVideo ? 
                        `<video src="${filePreviews[i]}" controls style="width:100%;height:100%;object-fit:cover;"></video>` :
                        `<img src="${filePreviews[i]}" style="width:100%;height:100%;object-fit:cover;">`
                    }
                </div>
            `;
        }
        
        if (mediaCount > 4) {
            mediaHtml += `<div class="facebook-media-item" style="background:#f0f2f5;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:bold;">+${mediaCount - 4}</div>`;
        }
        
        mediaHtml += `</div>`;
    }
    
    return `
        <div class="facebook-post-preview">
            <div class="facebook-post-header">
                <div class="facebook-avatar">${escapeHtml(pageInitial)}</div>
                <div style="flex:1;">
                    <div class="facebook-post-name">${escapeHtml(pageName)}</div>
                    <div class="facebook-post-time">${timeAgo} · 🌍</div>
                    ${locationHtml}
                </div>
            </div>
            <div class="facebook-post-content">
                ${escapeHtml(content) || 'No description provided'}
                ${mediaHtml}
            </div>
            <div class="facebook-actions">
                <div class="facebook-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    Like
                </div>
                <div class="facebook-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    Comment
                </div>
                <div class="facebook-action-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>
                    </svg>
                    Share
                </div>
            </div>
        </div>
    `;
}
function renderFacebookReelPreview(content, mediaFiles) {
    const pageName = selectedFacebookPage ? selectedFacebookPage.name : 'Facebook Page';
    const mediaUrl = mediaFiles.length > 0 ? filePreviews[0] : null;
    const isVideo = mediaFiles[0] && mediaFiles[0].type && mediaFiles[0].type.startsWith('video/');
    
    let locationHtml = '';
    if (selectedLocation && selectedLocation.name) {
        locationHtml = `
            <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span style="color: #ffffff; font-size: 12px; font-weight: 400;">${escapeHtml(selectedLocation.name)}</span>
            </div>
        `;
    }
    
    return `
        <div class="facebook-reel-preview" style="position:relative;background:#000;border-radius:16px;aspect-ratio:9/16;">
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#000;">
                ${mediaUrl ? 
                    (isVideo ? 
                        `<video src="${mediaUrl}" class="reel-media" autoplay muted loop controls style="width:100%;height:100%;object-fit:cover;"></video>` :
                        `<img src="${mediaUrl}" class="reel-media" style="width:100%;height:100%;object-fit:cover;">`
                    ) :
                    `<div style="color:white;">Reel Preview</div>`
                }
            </div>
            <div class="reel-overlay">
                <div class="reel-username" style="color: #ffffff; font-weight: 600;">${escapeHtml(pageName)}</div>
                <div class="reel-caption" style="color: #ffffff; font-size: 13px; margin-top: 4px;">${escapeHtml(content) || 'New reel'}</div>
                ${locationHtml}
            </div>
            <div class="reel-actions">
                <div class="reel-action" style="color: #ffffff;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <br>0
                </div>
                <div class="reel-action" style="color: #ffffff;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <br>0
                </div>
                <div class="reel-action" style="color: #ffffff;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>
                    </svg>
                    <br>0
                </div>
            </div>
        </div>
    `;
}
let currentCarouselIndex = 0;
let carouselImages = [];

function renderInstagramPostPreview(content, mediaFiles) {
    const username = selectedInstagramProfile ? selectedInstagramProfile.username : 'instagram_user';
    const initial = username.charAt(0).toUpperCase();
    const mediaCount = mediaFiles.length;
    
    let locationHtml = '';
    if (selectedLocation && selectedLocation.name) {
        const instagramLocationUrl = `https://www.instagram.com/explore/locations/?q=${encodeURIComponent(selectedLocation.name)}`;
        locationHtml = `
            <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px; margin-bottom: 8px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#8e8e8e" stroke-width="2">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <a href="${instagramLocationUrl}" target="_blank" style="color: #8e8e8e; font-size: 12px; text-decoration: none;">
                    ${escapeHtml(selectedLocation.name)}
                </a>
            </div>
        `;
    }
    
    let mediaHtml = '';
    
    if (mediaCount === 1 && mediaFiles[0]) {
        const isVideo = mediaFiles[0].type && mediaFiles[0].type.startsWith('video/');
        mediaHtml = `
            <div style="background:#fafafa;min-height:300px;display:flex;align-items:center;justify-content:center;">
                ${isVideo ? 
                    `<video src="${filePreviews[0]}" controls style="width:100%;max-height:400px;object-fit:cover;"></video>` :
                    `<img src="${filePreviews[0]}" style="width:100%;max-height:400px;object-fit:cover;">`
                }
            </div>
        `;
    } else if (mediaCount > 1) {
        carouselImages = filePreviews;
        currentCarouselIndex = 0;
        const firstFile = mediaFiles[0];
        const isVideo = firstFile && firstFile.type && firstFile.type.startsWith('video/');
        const carouselId = 'ig-carousel-' + Date.now();
        
        mediaHtml = `
            <div id="${carouselId}" style="position:relative;background:#fafafa;min-height:300px;display:flex;align-items:center;justify-content:center;">
                <div style="width:100%;display:flex;align-items:center;justify-content:center;">
                    ${isVideo ? 
                        `<video id="${carouselId}-video" src="${filePreviews[0]}" controls style="width:100%;max-height:400px;object-fit:cover;"></video>` :
                        `<img src="${filePreviews[0]}" style="width:100%;max-height:400px;object-fit:cover;">`
                    }
                </div>
                <div onclick="event.stopPropagation(); changeInstagramCarousel(-1, '${carouselId}')" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:30px;height:30px;background:rgba(0,0,0,0.5);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:white;z-index:10;font-size:20px;">‹</div>
                <div onclick="event.stopPropagation(); changeInstagramCarousel(1, '${carouselId}')" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);width:30px;height:30px;background:rgba(0,0,0,0.5);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:white;z-index:10;font-size:20px;">›</div>
                <div style="position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,0.6);color:white;padding:4px 8px;border-radius:16px;font-size:12px;z-index:10;">1/${mediaCount}</div>
            </div>
        `;
    }
    
    return `
        <div class="instagram-post-preview">
            <div class="instagram-header">
                <div class="instagram-user-info">
                    <div class="instagram-avatar">
                        <div style="width:28px;height:28px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;color:#e2346e;font-weight:bold;">${escapeHtml(initial)}</div>
                    </div>
                    <div class="instagram-username">${escapeHtml(username)}</div>
                </div>
                <div>•••</div>
            </div>
            ${mediaHtml}
            ${locationHtml}
            <div class="instagram-actions">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>
                </svg>
                <span style="margin-left:auto;">❤️ 0 likes</span>
            </div>
            <div class="instagram-caption">
                <strong>${escapeHtml(username)}</strong> ${escapeHtml(content) || 'No caption'}
            </div>
        </div>
    `;
}

function changeInstagramCarousel(direction, carouselId) {
    const mediaCount = carouselImages.length;
    if (mediaCount === 0) return;
    
    currentCarouselIndex = (currentCarouselIndex + direction + mediaCount) % mediaCount;
    
    const container = document.getElementById(carouselId);
    if (!container) return;
    
    const mediaDiv = container.querySelector('div:first-child');
    const currentFile = selectedFiles[currentCarouselIndex];
    const isVideo = currentFile && currentFile.type && currentFile.type.startsWith('video/');
    
    if (isVideo) {
        mediaDiv.innerHTML = `<video id="${carouselId}-video" src="${carouselImages[currentCarouselIndex]}" controls style="width:100%;max-height:400px;object-fit:cover;"></video>`;
        const video = document.getElementById(`${carouselId}-video`);
        if (video) video.load();
    } else {
        mediaDiv.innerHTML = `<img src="${carouselImages[currentCarouselIndex]}" style="width:100%;max-height:400px;object-fit:cover;">`;
    }
    
    const counter = container.querySelector('div:last-child');
    if (counter) {
        counter.innerHTML = `${currentCarouselIndex + 1}/${mediaCount}`;
    }
}
function renderInstagramReelPreview(content, mediaFiles) {
    const username = selectedInstagramProfile ? selectedInstagramProfile.username : 'instagram_user';
    const mediaUrl = mediaFiles.length > 0 ? filePreviews[0] : null;
    const isVideo = mediaFiles[0] && mediaFiles[0].type && mediaFiles[0].type.startsWith('video/');
    
    let locationHtml = '';
    if (selectedLocation && selectedLocation.name) {
        locationHtml = `<div class="reel-caption" style="margin-top: 4px;">📍 ${escapeHtml(selectedLocation.name)}</div>`;
    }
    
    return `
        <div class="instagram-reel-preview" style="position:relative;background:#000;border-radius:16px;aspect-ratio:9/16;">
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#000;">
                ${mediaUrl ? 
                    (isVideo ? 
                        `<video src="${mediaUrl}" class="reel-media" autoplay muted loop controls style="width:100%;height:100%;object-fit:cover;"></video>` :
                        `<img src="${mediaUrl}" class="reel-media" style="width:100%;height:100%;object-fit:cover;">`
                    ) :
                    `<div style="color:white;">Reel Preview</div>`
                }
            </div>
            <div class="reel-overlay">
                <div class="reel-username">${escapeHtml(username)}</div>
                <div class="reel-caption">${escapeHtml(content) || 'New reel'}</div>
                ${locationHtml}
            </div>
            <div class="reel-actions">
                <div class="reel-action">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <br>0
                </div>
                <div class="reel-action">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <br>0
                </div>
                <div class="reel-action">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>
                    </svg>
                    <br>0
                </div>
            </div>
        </div>
    `;
}
function renderFacebookStoryPreview(mediaFiles) {
    const pageName = selectedFacebookPage ? selectedFacebookPage.name : 'Facebook Page';
    const pageInitial = pageName.charAt(0).toUpperCase();
    const mediaUrl = mediaFiles.length > 0 ? filePreviews[0] : null;
    
    let locationHtml = '';
    if (selectedLocation && selectedLocation.name) {
        locationHtml = `
            <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span style="font-size: 11px; opacity: 0.9;">${escapeHtml(selectedLocation.name)}</span>
            </div>
        `;
    }
    
    return `
        <div class="facebook-story-preview">
            <div class="story-progress-bar">
                <div class="story-progress"></div>
            </div>
            <div class="story-header">
                <div class="story-avatar">
                    <div style="width:36px;height:36px;border-radius:50%;background:#1877F2;display:flex;align-items:center;justify-content:center;color:white;">${escapeHtml(pageInitial)}</div>
                </div>
                <div>
                    <div class="story-name">${escapeHtml(pageName)}</div>
                    ${locationHtml}
                </div>
            </div>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                ${mediaUrl ? 
                    (selectedFiles[0]?.type?.startsWith('video/') ? 
                        `<video src="${mediaUrl}" style="width:100%;height:100%;object-fit:cover;"></video>` :
                        `<img src="${mediaUrl}" style="width:100%;height:100%;object-fit:cover;">`
                    ) :
                    `<div style="color:white;">Story Preview</div>`
                }
            </div>
        </div>
    `;
}
function renderInstagramStoryPreview(mediaFiles) {
    const username = selectedInstagramProfile ? selectedInstagramProfile.username : 'instagram_user';
    const initial = username.charAt(0).toUpperCase();
    const mediaUrl = mediaFiles.length > 0 ? filePreviews[0] : null;
    
    let locationHtml = '';
    if (selectedLocation && selectedLocation.name) {
        locationHtml = `<div style="font-size:12px; margin-top:4px;">📍 ${escapeHtml(selectedLocation.name)}</div>`;
    }
    
    return `
        <div class="instagram-story-preview">
            <div class="story-progress-bar">
                <div class="story-progress"></div>
            </div>
            <div class="story-header">
                <div class="story-avatar">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#fccc63,#e2346e,#ac2b9e);display:flex;align-items:center;justify-content:center;color:white;">${escapeHtml(initial)}</div>
                </div>
                <div class="story-name">${escapeHtml(username)}</div>
            </div>
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                ${mediaUrl ? 
                    (selectedFiles[0]?.type?.startsWith('video/') ? 
                        `<video src="${mediaUrl}" style="width:100%;height:100%;object-fit:cover;"></video>` :
                        `<img src="${mediaUrl}" style="width:100%;height:100%;object-fit:cover;">`
                    ) :
                    `<div style="color:white;">Story Preview</div>`
                }
            </div>
            <div class="story-header" style="bottom:20px; top:auto;">
                <div class="story-name">${locationHtml}</div>
            </div>
        </div>
    `;
}
function renderYouTubePreview(content, mediaFiles) {
    const channelName = selectedYoutubeChannel ? selectedYoutubeChannel.name : 'YouTube Channel';
    const channelInitial = channelName.charAt(0).toUpperCase();
    const mediaUrl = mediaFiles.length > 0 ? filePreviews[0] : null;
    const isVideo = mediaFiles[0] && mediaFiles[0].type && mediaFiles[0].type.startsWith('video/');
    const videoTitle = content && content.trim() !== '' ? content : 'Untitled Video';
    
    let mediaHtml = '';
    
    if (mediaUrl && isVideo) {
        mediaHtml = `
            <div style="background:#000;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;">
                <video src="${mediaUrl}" controls style="width:100%;height:100%;object-fit:contain;"></video>
            </div>
        `;
    } else if (mediaUrl) {
        mediaHtml = `
            <div class="youtube-thumbnail" style="aspect-ratio:16/9;position:relative;">
                <img src="${mediaUrl}" style="width:100%;height:100%;object-fit:cover;">
                <div class="youtube-play-icon">
                    <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </div>
        `;
    } else {
        mediaHtml = `
            <div class="youtube-thumbnail" style="aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;background:#1a1a1a;">
                <div style="color:#aaa;">Video Thumbnail</div>
                <div class="youtube-play-icon">
                    <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </div>
        `;
    }
    
    return `
        <div class="youtube-preview">
            ${mediaHtml}
            <div class="youtube-info">
                <div class="youtube-title">${escapeHtml(videoTitle)}</div>
                <div class="youtube-channel">
                    <div class="youtube-channel-avatar">
                        <div style="width:36px;height:36px;border-radius:50%;background:#ff0000;display:flex;align-items:center;justify-content:center;color:white;">${escapeHtml(channelInitial)}</div>
                    </div>
                    <div class="youtube-channel-name">${escapeHtml(channelName)}</div>
                </div>
                <div class="youtube-description">${escapeHtml(content) || 'No description'} • 0 views • Just now</div>
            </div>
        </div>
    `;
}

// Wait for DOM
document.addEventListener('DOMContentLoaded', () => {

const btns = document.querySelectorAll('.platform-btn');
const platformsInput = document.getElementById('selectedPlatformsInput');
const contentInput = document.getElementById('contentInput');
const postText = document.getElementById('postText');
const charCount = document.getElementById('charCount');
const facebookBox = document.getElementById('facebookPageBox');
const instagramBox = document.getElementById('instagramUrlBox');
const youtubeBox = document.getElementById('youtubeBox');
const preview = document.getElementById('previewContainer');
const emptyPreview = document.getElementById('emptyPreview');
const multiFileInput = document.getElementById('imageInput');
const previewGrid = document.getElementById('mediaPreviewGrid');
const addMoreBtn = document.getElementById('addMoreBtn');

// Helper functions
function getPlatformColor(platform) {
    const colors = {
        'facebook': 'bg-blue-100 text-blue-600',
        'instagram': 'bg-pink-100 text-pink-600',
        'youtube': 'bg-red-100 text-red-600'
    };
    return colors[platform] || 'bg-gray-100 text-gray-600';
}

function getPlatformIcon(platform) {
    const icons = {
        'facebook': 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
        'instagram': 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z',
        'youtube': 'M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z'
    };
    return icons[platform] || '';
}

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg text-white font-medium animate-fadeIn ${type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
    alertDiv.innerHTML = message;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 3000);
}


function updateInstagramPreview() {
    const profileSelect = document.getElementById('instagramProfileSelect');
    if (profileSelect && profileSelect.value) {
        const selectedOption = profileSelect.options[profileSelect.selectedIndex];
        selectedInstagramProfile = {
            id: profileSelect.value,
            username: selectedOption.textContent
        };
    } else {
        selectedInstagramProfile = null;
    }
    renderPreview();
}

function updateYoutubePreview() {
    const channelSelect = document.getElementById('youtubeAccountSelect');
    if (channelSelect && channelSelect.value) {
        const selectedOption = channelSelect.options[channelSelect.selectedIndex];
        selectedYoutubeChannel = {
            id: channelSelect.value,
            name: selectedOption.textContent
        };
    } else {
        selectedYoutubeChannel = null;
    }
    renderPreview();
}

// Facebook Post Type Change
document.querySelectorAll('input[name="facebook_post_type"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
        facebookPostType = e.target.value;
        renderPreview();
    });
});

// Instagram Post Type Change
document.querySelectorAll('input[name="ig_post_type"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
        instagramPostType = e.target.value;
        renderPreview();
    });
});

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Main render function

// Platform selection
btns.forEach(btn => {
    btn.onclick = () => {
        const key = btn.dataset.key;
        btn.classList.toggle('active');
        
        if (selected.includes(key)) {
            selected = selected.filter(x => x !== key);
        } else {
            selected.push(key);
        }
        
        if (platformsInput) platformsInput.value = JSON.stringify(selected);
        
        if (facebookBox) facebookBox.style.display = selected.includes('facebook') ? 'block' : 'none';
        if (instagramBox) instagramBox.style.display = selected.includes('instagram') ? 'block' : 'none';
        if (youtubeBox) youtubeBox.style.display = selected.includes('youtube') ? 'block' : 'none';
        
        if (!selected.includes('facebook')) selectedFacebookPage = null;
        if (!selected.includes('instagram')) selectedInstagramProfile = null;
        if (!selected.includes('youtube')) selectedYoutubeChannel = null;
        
        renderPreview();
    };
});

// Character counter
if (postText) {
    postText.oninput = () => {
        const count = postText.innerText.length;
        if (charCount) {
            charCount.innerText = `${count}/280`;
            charCount.className = `text-sm font-medium ${count > 280 ? 'text-red-500' : 'text-gray-500'}`;
        }
        if (contentInput) contentInput.value = postText.innerHTML;
        renderPreview();
    };
}

// Instagram Profile Change
const instagramProfileSelect = document.getElementById('instagramProfileSelect');
if (instagramProfileSelect) {
    instagramProfileSelect.addEventListener('change', updateInstagramPreview);
}

// YouTube Channel Change
const youtubeChannelSelect = document.getElementById('youtubeAccountSelect');
if (youtubeChannelSelect) {
    youtubeChannelSelect.addEventListener('change', updateYoutubePreview);
}

// Form submit
const postForm = document.getElementById('postForm');
if (postForm) {
    postForm.onsubmit = (e) => {
        if (contentInput) contentInput.value = postText ? postText.innerHTML : '';
        
        if (!selected.length) {
            showAlert('Please select at least one platform', 'error');
            e.preventDefault();
            return false;
        }
        
        if (selected.includes('instagram') && selectedFiles.length === 0 && instagramPostType !== 'story') {
            showAlert('Instagram post requires image upload', 'error');
            e.preventDefault();
            return false;
        }
        
        if (selected.includes('facebook')) {
            const fbType = document.querySelector('input[name="facebook_post_type"]:checked')?.value || 'post';
            const hasMedia = selectedFiles.length > 0;
            const hasContent = postText ? postText.innerText.trim().length > 0 : false;
            
            if ((fbType === 'story' || fbType === 'reel') && !hasMedia) {
                showAlert('Facebook story/reel requires media upload', 'error');
                e.preventDefault();
                return false;
            }
            
            if (fbType === 'post' && !hasMedia && !hasContent) {
                showAlert('Please add content or media for Facebook post', 'error');
                e.preventDefault();
                return false;
            }
        }
        return true;
    };
}

if (multiFileInput) {
    multiFileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        files.forEach(file => {
            if (file.type.startsWith('image/') || file.type.startsWith('video/')) {
                selectedFiles.push(file);
                const reader = new FileReader();
                reader.onload = function(ev) {
                    filePreviews.push(ev.target.result);
                    updateMediaGrid();
                    renderPreview();
                };
                reader.readAsDataURL(file);
            } else {
                showAlert(`${file.name} is not an image or video`, 'error');
            }
        });
        const dt = new DataTransfer();

        selectedFiles.forEach(file => {
            dt.items.add(file);
        });

        multiFileInput.files = dt.files;
    });
    
}

function updateMediaGrid() {
    if (!previewGrid) return;
    
    const mediaCountElem = document.getElementById('mediaCount');
    if (mediaCountElem) mediaCountElem.innerText = `${filePreviews.length}/10`;
    
    if (filePreviews.length === 0) {
        previewGrid.innerHTML = '';
        if (addMoreBtn) addMoreBtn.classList.add('hidden');
        renderPreview();
        return;
    }
    
    if (addMoreBtn) addMoreBtn.classList.remove('hidden');
    
    let html = '';
    filePreviews.forEach((src, index) => {
        const file = selectedFiles[index];
        const isVideo = file && file.type && file.type.startsWith('video/');
        html += `
            <div class="relative aspect-square rounded-xl overflow-hidden border bg-gray-100">
                ${isVideo ? 
                    `<video src="${src}" class="w-full h-full object-cover" muted></video>` : 
                    `<img src="${src}" class="w-full h-full object-cover">`
                }
                <button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-600 rounded-full text-white text-sm hover:bg-red-700 flex items-center justify-center" onclick="removeImage(${index})">×</button>
                <div class="absolute bottom-1 left-1 bg-black/60 text-white text-xs px-2 py-0.5 rounded-full">${isVideo ? '🎬' : (index + 1)}</div>
            </div>
        `;
    });
    previewGrid.innerHTML = html;
    renderPreview();
}

// Remove image
window.removeImage = function(index) {
    selectedFiles.splice(index, 1);
    filePreviews.splice(index, 1);
    
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    if (multiFileInput) multiFileInput.files = dt.files;
    
    updateMediaGrid();
};

// Schedule box
const statusSelect = document.querySelector('select[name="status"]');
const scheduleBoxElem = document.getElementById('scheduleBox');
if (statusSelect && scheduleBoxElem) {
    statusSelect.addEventListener('change', function() {
        if (this.value === 'scheduled') {
            scheduleBoxElem.classList.remove('hidden');
        } else {
            scheduleBoxElem.classList.add('hidden');
        }
    });
}

// Instagram & Facebook hints
function updateInstagramHint() {
    const selectedHint = document.querySelector('input[name="ig_post_type"]:checked');
    const hint = document.getElementById('igPostTypeHint');
    if (selectedHint && hint) {
        if (selectedHint.value === 'post') hint.textContent = 'Post: Static image only (JPG/PNG)';
        else if (selectedHint.value === 'story') hint.textContent = 'Story: Image or video (max 15 seconds)';
        else if (selectedHint.value === 'reel') hint.textContent = 'Reel: Video only (15-90 seconds, MP4/MOV)';
        instagramPostType = selectedHint.value;
        renderPreview();
    }
}

function updateFacebookHint() {
    const selectedHint = document.querySelector('input[name="facebook_post_type"]:checked');
    const hint = document.getElementById('fbPostTypeHint');
    if (selectedHint && hint) {
        if (selectedHint.value === 'post') hint.textContent = 'Post: Regular Facebook feed post';
        else if (selectedHint.value === 'story') hint.textContent = 'Story: 9:16 format recommended, max 60 sec';
        else if (selectedHint.value === 'reel') hint.textContent = 'Reel: Vertical video recommended for reels';
        facebookPostType = selectedHint.value;
        renderPreview();
    }
}

document.querySelectorAll('input[name="ig_post_type"]').forEach(radio => {
    radio.addEventListener('change', updateInstagramHint);
});
document.querySelectorAll('input[name="facebook_post_type"]').forEach(radio => {
    radio.addEventListener('change', updateFacebookHint);
});

// Initial render
renderPreview();

});

// Emoji picker (outside DOMContentLoaded)
let emojiPicker = null;

function initEmojiPicker() {
    const emojiBtn = document.getElementById('emojiBtn');
    if (!emojiBtn) return;
    
    if (emojiPicker) emojiPicker.remove();
    
    emojiPicker = new EmojiMart.Picker({
        onEmojiSelect: (emoji) => {
            insertEmojiAtCursor(emoji.native);
            emojiPicker.style.display = 'none';
        },
        theme: 'light',
        showPreview: false,
        showSkinTones: false,
        emojiSize: 24,
        perLine: 8
    });
    
    emojiPicker.style.position = 'fixed';
    emojiPicker.style.width = '280px';
    emojiPicker.style.background = 'white';
    emojiPicker.style.borderRadius = '12px';
    emojiPicker.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
    emojiPicker.style.zIndex = '100000';
    emojiPicker.style.display = 'none';
    document.body.appendChild(emojiPicker);
    
    emojiBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const rect = emojiBtn.getBoundingClientRect();
        if (emojiPicker.style.display === 'block') {
            emojiPicker.style.display = 'none';
        } else {
            let top = rect.bottom + 5;
            let left = rect.left;
            if (top + 350 > window.innerHeight) top = rect.top - 355;
            if (left + 280 > window.innerWidth) left = window.innerWidth - 290;
            if (left < 5) left = 5;
            emojiPicker.style.top = top + 'px';
            emojiPicker.style.left = left + 'px';
            emojiPicker.style.display = 'block';
        }
    });
    
    document.body.addEventListener('click', (e) => {
        if (!emojiBtn.contains(e.target) && emojiPicker.style.display === 'block') {
            emojiPicker.style.display = 'none';
        }
    });
}

function insertEmojiAtCursor(emoji) {
    const editor = document.getElementById('postText');
    if (!editor) return;
    editor.focus();
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();
        range.insertNode(document.createTextNode(emoji));
        range.collapse(false);
    } else {
        editor.innerText += emoji;
    }
    editor.dispatchEvent(new Event('input'));
}

initEmojiPicker();
let selectedLocation = null;
let locationSearchTimeout = null;

function openLocationModal() {
    document.getElementById('locationModal').classList.remove('hidden');
    document.getElementById('locationSearchInput').focus();
}

function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
    document.getElementById('locationResults').innerHTML = '<div class="p-4 text-center text-gray-500">Type to search locations</div>';
    document.getElementById('locationSearchInput').value = '';
}

function openHashtagModal() {
    document.getElementById('hashtagModal').classList.remove('hidden');
    document.getElementById('hashtagSearchInput').focus();
}

function closeHashtagModal() {
    document.getElementById('hashtagModal').classList.add('hidden');
    document.getElementById('hashtagResults').innerHTML = '<div class="p-4 text-center text-gray-500">Type to search hashtags</div>';
    document.getElementById('hashtagSearchInput').value = '';
}

function selectLocation(location) {
    selectedLocation = location;
    document.getElementById('locationIdInput').value = location.id;
    document.getElementById('locationNameInput').value = location.name;
    const displayDiv = document.getElementById('selectedLocationDisplay');
    displayDiv.innerHTML = `
        <div class="location-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>${escapeHtml(location.name)}</span>
            <span class="remove-location" onclick="removeLocation()">×</span>
        </div>
    `;
    displayDiv.classList.remove('hidden');
    closeLocationModal();
    renderPreview();
}
function removeLocation() {
    selectedLocation = null;
    document.getElementById('locationIdInput').value = '';
    document.getElementById('locationNameInput').value = '';
    document.getElementById('selectedLocationDisplay').classList.add('hidden');
    renderPreview();
}

function insertHashtag(hashtag) {
    const editor = document.getElementById('postText');
    if (!editor) return;
    
    editor.focus();
    const currentText = editor.innerText;
    const newText = currentText + (currentText ? ' ' : '') + '#' + hashtag;
    editor.innerText = newText;
    
    const range = document.createRange();
    const selection = window.getSelection();
    range.selectNodeContents(editor);
    range.collapse(false);
    selection.removeAllRanges();
    selection.addRange(range);
    
    editor.dispatchEvent(new Event('input'));
    closeHashtagModal();
}

document.getElementById('locationBtn')?.addEventListener('click', openLocationModal);
document.getElementById('hashtagBtn')?.addEventListener('click', openHashtagModal);

document.getElementById('locationSearchInput')?.addEventListener('input', function() {
    const query = this.value.trim();
    const resultsDiv = document.getElementById('locationResults');
    
    if (locationSearchTimeout) clearTimeout(locationSearchTimeout);
    
    if (query.length < 2) {
        resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">Type at least 2 characters to search</div>';
        return;
    }
    
    resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">Searching locations...</div>';
    
    locationSearchTimeout = setTimeout(() => {
        fetch(`/ajax/location-search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (!data || data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">No locations found</div>';
                    return;
                }
                
                const seen = new Set();
                const uniqueLocations = [];
                
                for (const loc of data) {
                    const key = loc.id;
                    if (!seen.has(key)) {
                        seen.add(key);
                        uniqueLocations.push(loc);
                    }
                }
                
                resultsDiv.innerHTML = uniqueLocations.map(location => `
                    <div class="suggestion-item" onclick='selectLocation(${JSON.stringify(location).replace(/</g, '\\u003c')})'>
                        <div style="flex:1;">
                            <div class="suggestion-name" style="font-weight:600;">${escapeHtml(location.name)}</div>
                            <div class="suggestion-meta" style="font-size:12px;color:#65676b;">${escapeHtml(location.full_address || location.address || '')}</div>
                            ${location.checkin_text ? `<div class="suggestion-meta" style="font-size:11px;color:#65676b;margin-top:2px;">📍 ${escapeHtml(location.checkin_text)}</div>` : ''}
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </div>
                `).join('');
            })
            .catch(() => {
                resultsDiv.innerHTML = '<div class="p-4 text-center text-red-500">Error loading locations. Please try again.</div>';
            });
    }, 500);
});
document.getElementById('hashtagSearchInput')?.addEventListener('input', function() {
    const query = this.value;
    
    if (locationSearchTimeout) clearTimeout(locationSearchTimeout);
    
    if (query.length < 2) {
        document.getElementById('hashtagResults').innerHTML = '<div class="p-4 text-center text-gray-500">Type at least 2 characters</div>';
        return;
    }
    
    locationSearchTimeout = setTimeout(() => {
        fetch(`/ajax/hashtag-suggestions?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                const resultsDiv = document.getElementById('hashtagResults');
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">No hashtags found</div>';
                    return;
                }
                
                resultsDiv.innerHTML = data.map(tag => `
                    <div class="suggestion-item" onclick="insertHashtag('${escapeHtml(tag.name)}')">
                        <div>
                            <div class="suggestion-name">#${escapeHtml(tag.name)}</div>
                            <div class="suggestion-count">${typeof tag.count === 'number' ? tag.count.toLocaleString() : tag.count} posts</div>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </div>
                `).join('');
            })
            .catch(() => {
                document.getElementById('hashtagResults').innerHTML = '<div class="p-4 text-center text-red-500">Error loading hashtags</div>';
            });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('#locationModal') && !e.target.closest('#locationBtn')) {
        document.getElementById('locationModal')?.classList.add('hidden');
    }
    if (!e.target.closest('#hashtagModal') && !e.target.closest('#hashtagBtn')) {
        document.getElementById('hashtagModal')?.classList.add('hidden');
    }
});

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush