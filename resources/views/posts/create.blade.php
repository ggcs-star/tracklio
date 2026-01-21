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
                </select>
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
                    'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
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
        <input type="hidden" name="content" id="contentInput">

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
                <p class="text-sm text-gray-500">Select which page to publish to</p>
            </div>
        </div>
        <select name="facebook_page_id" id="facebookPageSelect"
            class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
            <option value="">-- Select a Page --</option>
            @foreach($facebookPages as $page)
                <option value="{{ $page['page_id'] }}">
                    {{ $page['page_name'] ?? 'Facebook Page' }}
                </option>
            @endforeach
        </select>
    </div>
    @endif

    {{-- INSTAGRAM URL --}}
    <div id="instagramUrlBox" class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hidden animate-slideDown">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg p-2">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Instagram Media</h3>
                <p class="text-sm text-gray-500">Add your media URL</p>
            </div>
        </div>
        <input type="url" name="media_url" id="instagramMediaUrl"
            class="w-full border-gray-300 rounded-xl px-4 py-3 text-sm bg-white shadow-sm focus:border-pink-500 focus:ring-pink-500 transition-colors"
            placeholder="https://example.com/image-or-video.jpg">
        <p class="text-xs text-gray-500 mt-3 flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Instagram requires a <b class="font-semibold ml-1">public image or video URL</b>
        </p>
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
        
        <textarea id="postText"
            class="w-full p-5 border border-gray-300 rounded-xl min-h-[180px] text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 resize-none transition-colors"
            placeholder="What would you like to share?"></textarea>
    </div>

    {{-- MEDIA UPLOAD --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Media Attachment</h3>
        
        <label for="imageInput" class="group relative border-3 border-dashed border-gray-300 hover:border-blue-400 rounded-2xl p-8 text-center cursor-pointer transition-all duration-300 hover:bg-blue-50/50 block mb-4">
            <input type="file" name="media" id="imageInput"
                accept="image/*,video/*" hidden>
            
            <div class="flex flex-col items-center gap-4">
                <div class="bg-blue-100 group-hover:bg-blue-200 rounded-full p-4 transition-colors">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                
                <div>
                    <p class="font-medium text-gray-900">Upload Image or Video</p>
                    <p class="text-sm text-gray-500 mt-1">Supports JPG, PNG, MP4, MOV</p>
                </div>
                
               <button type="button"
    onclick="document.getElementById('imageInput').click()"
    class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors">
    Choose File
</button>

            </div>
        </label>

        <div id="uploadedPreview" class="hidden animate-slideDown">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 rounded-lg p-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                            <p class="text-xs text-gray-500">Ready to publish</p>
                        </div>
                    </div>
                    <button id="removeMedia" type="button"
                        class="text-sm text-red-600 hover:text-red-700 font-medium px-3 py-1 rounded-lg hover:bg-red-50 transition-colors">
                        Remove
                    </button>
                </div>
            </div>
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

const btns = document.querySelectorAll('.platform-btn');
const platformsInput = document.getElementById('selectedPlatformsInput');
const contentInput = document.getElementById('contentInput');
const postText = document.getElementById('postText');
const charCount = document.getElementById('charCount');

const facebookBox = document.getElementById('facebookPageBox');
const instagramBox = document.getElementById('instagramUrlBox');
const instagramUrl = document.getElementById('instagramMediaUrl');

const preview = document.getElementById('previewContainer');
const emptyPreview = document.getElementById('emptyPreview');

const fileInput = document.getElementById('imageInput');
const uploadedPreview = document.getElementById('uploadedPreview');
const fileNameEl = document.getElementById('fileName');
const removeMedia = document.getElementById('removeMedia');

let selected = [];
let fileUrl = null;

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
        
        platformsInput.value = JSON.stringify(selected);
        
        // Show/hide platform-specific fields
        if (facebookBox) {
            facebookBox.style.display = selected.includes('facebook') ? 'block' : 'none';
        }
        if (instagramBox) {
            instagramBox.style.display = selected.includes('instagram') ? 'block' : 'none';
        }
        
        render();
    };
});

// Character counter
postText.oninput = () => {
    const count = postText.value.length;
    charCount.innerText = `${count}/280`;
    charCount.className = `text-sm font-medium ${count > 280 ? 'text-red-500' : 'text-gray-500'}`;
    contentInput.value = postText.value;
    render();
};

// File upload
fileInput.onchange = () => {
    if (!fileInput.files[0]) return;
    
    const file = fileInput.files[0];
    
    // Validation
    if (selected.includes('youtube') && !file.type.startsWith('video')) {
        showAlert('YouTube requires a video file', 'error');
        fileInput.value = '';
        return;
    }
    
    fileUrl = URL.createObjectURL(file);
    fileNameEl.innerText = file.name;
    uploadedPreview.classList.remove('hidden');
    render();
};

// Remove media
removeMedia.onclick = () => {
    if (fileUrl) {
        URL.revokeObjectURL(fileUrl);
    }
    fileUrl = null;
    fileInput.value = '';
    uploadedPreview.classList.add('hidden');
    render();
};

// Form validation
document.getElementById('postForm').onsubmit = e => {
    if (!selected.length) {
        showAlert('Please select at least one platform', 'error');
        e.preventDefault();
        return;
    }
    
    if (selected.includes('instagram') && !instagramUrl.value) {
        showAlert('Instagram requires a media URL', 'error');
        e.preventDefault();
        return;
    }
    
    if (selected.includes('facebook')) {
        const page = document.getElementById('facebookPageSelect');
        if (page && !page.value) {
            showAlert('Please select a Facebook Page', 'error');
            e.preventDefault();
            return;
        }
    }
    
    contentInput.value = postText.value;
};

// Render preview
function render() {
    preview.innerHTML = '';
    
    if (!selected.length) {
        emptyPreview.style.display = 'block';
        return;
    }
    
    emptyPreview.style.display = 'none';
    
    selected.forEach(platform => {
        const title = platform.charAt(0).toUpperCase() + platform.slice(1);
        let html = `
            <div class="preview-card">
                <div class="flex items-center gap-3 mb-3">
                    <div class="${getPlatformColor(platform)} rounded-lg p-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            ${getPlatformIcon(platform)}
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-900">${title}</span>
                </div>
                <p class="text-gray-700 text-sm">${postText.value || 'Your content will appear here...'}</p>`;
        
        // Instagram media preview
        if (platform === 'instagram' && instagramUrl.value) {
            if (instagramUrl.value.endsWith('.mp4') || instagramUrl.value.includes('video')) {
                html += `
                    <div class="relative mt-3">
                        <video src="${instagramUrl.value}" controls class="preview-img"></video>
                        <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            Video
                        </div>
                    </div>`;
            } else {
                html += `
                    <div class="relative mt-3">
                        <img src="${instagramUrl.value}" class="preview-img" alt="Instagram media">
                        <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            Image
                        </div>
                    </div>`;
            }
        }
        
        // Uploaded media preview
        if (fileUrl) {
            if (fileUrl.endsWith('.mp4') || fileInput.files[0]?.type.startsWith('video')) {
                html += `
                    <div class="relative mt-3">
                        <video src="${fileUrl}" controls class="preview-img"></video>
                        <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            Video
                        </div>
                    </div>`;
            } else {
                html += `
                    <div class="relative mt-3">
                        <img src="${fileUrl}" class="preview-img" alt="Uploaded media">
                        <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                            Image
                        </div>
                    </div>`;
            }
        }
        
        html += `</div>`;
        preview.innerHTML += html;
    });
}

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
        'instagram': 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
        'youtube': 'M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z'
    };
    return icons[platform] || '';
}

function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg text-white font-medium animate-fadeIn ${
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    alert.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            ${message}
        </div>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Initialize
render();

});
</script>
@endpush