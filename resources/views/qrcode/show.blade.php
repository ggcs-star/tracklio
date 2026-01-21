@extends('layouts.index')

@section('title', 'QR Details')

@section('content')

<div x-data="qrDesigner()" x-init="init()" class="space-y-8">

{{-- ================= ENHANCED HEADER ================= --}}
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
    <div>
        <div class="flex items-center gap-4 mb-3">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <i data-lucide="qr-code" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">QR Code Details</h1>
                <p class="text-gray-600 mt-1">Manage, customize, and track your QR code</p>
            </div>
        </div>
        
        {{-- Breadcrumb --}}
        <div class="flex items-center text-sm text-gray-500 mt-4">
            <a href="{{ route('qr-links.index') }}" class="hover:text-indigo-600 transition-colors">QR Codes</a>
            <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-indigo-600 font-medium">Details</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('qr-links.index') }}"
           class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 flex items-center gap-2 font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to List
        </a>

        <button
            onclick="document.getElementById('editForm').scrollIntoView({behavior:'smooth'})"
            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl font-medium shadow hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <i data-lucide="edit-3" class="w-4 h-4"></i>
            Edit QR
        </button>
    </div>
</div>

{{-- ================= ENHANCED STATS OVERVIEW ================= --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i data-lucide="mouse-pointer-click" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Visits</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $qr->visit_count }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                <i data-lucide="scan-line" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">QR Scans</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $qr->qr_scan_count ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6 text-green-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Status</p>
                <p class="text-lg font-semibold text-green-600 mt-1">Active</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center">
                <i data-lucide="calendar" class="w-6 h-6 text-gray-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Created</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $qr->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- ================= ENHANCED MAIN GRID ================= --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- ENHANCED DETAILS CARD --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                <i data-lucide="info" class="w-5 h-5 text-indigo-600"></i>
                QR Code Information
            </h2>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="space-y-1">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Label</p>
                <p class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    @if($qr->label)
                    <i data-lucide="tag" class="w-4 h-4 text-gray-400"></i>
                    {{ $qr->label }}
                    @else
                    <span class="text-gray-400">— No label set —</span>
                    @endif
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Destination URL</p>
                <a href="{{ $qr->original_url }}" target="_blank"
                   class="text-blue-600 hover:text-blue-800 break-all font-medium hover:underline flex items-center gap-2 group">
                    <i data-lucide="external-link" class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    {{ $qr->original_url }}
                </a>
                <p class="text-xs text-gray-500 mt-1">
                    <i data-lucide="globe" class="w-3 h-3 inline mr-1"></i>
                   @if($qr->original_url)
    {{ parse_url($qr->original_url, PHP_URL_HOST) }}
@else
    —
@endif

                </p>
            </div>

            <div class="space-y-1">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Short Link</p>
                <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl">
                <a href="{{ url('/q/'.$qr->short_code) }}" target="_blank">
    {{ url('/q/'.$qr->short_code) }}
</a>


                    <button
onclick="copyToClipboard('{{ url('/q/'.$qr->short_code) }}')"
                        class="text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition-colors group/copy"
                        title="Copy link">
                        <i data-lucide="copy" class="w-4 h-4 group-hover/copy:scale-110 transition-transform"></i>
                    </button>
                </div>
            </div>

            {{-- QR INFO GRID --}}
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-100">
                <div class="space-y-1">
                    <p class="text-xs text-gray-500">Short Code</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $qr->short_code }}</p>

                </div>
                <div class="space-y-1">
                    <p class="text-xs text-gray-500">QR Type</p>
                    <p class="font-semibold text-gray-900">Dynamic QR</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs text-gray-500">Last Updated</p>
                    <p class="font-semibold text-gray-900">{{ $qr->updated_at->diffForHumans() }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs text-gray-500">QR ID</p>
                    <p class="font-mono text-sm text-gray-600 truncate">{{ $qr->_id }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ENHANCED QR PREVIEW CARD --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                <i data-lucide="eye" class="w-5 h-5 text-indigo-600"></i>
                QR Code Preview
            </h2>
        </div>
        
        <div class="p-6 flex flex-col items-center justify-center">
            <div class="relative mb-6">
                <div
                    class="border-2 border-gray-200 rounded-2xl p-6 bg-gradient-to-br from-white to-gray-50 shadow-inner transition-all duration-500"
                    :style="`background:${bgColor}; transform: rotate(${qrRotate}deg);`">
                    
                    <div class="flex justify-center items-center mt-4">
    <div
        id="qrPreview"
        class="w-[260px] h-[260px] bg-white border rounded-xl shadow flex items-center justify-center">
        {{-- QR code will be rendered here --}}
    </div>
</div>

                </div>
                
                {{-- Scan Guide --}}
                <div class="absolute -top-3 -right-3 bg-indigo-600 text-white text-xs px-3 py-1.5 rounded-full font-medium animate-pulse">
                    <i data-lucide="scan" class="w-3 h-3 inline mr-1"></i>
                    Scan Me
                </div>
            </div>

            {{-- Preview Info --}}
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-1">This QR code points to:</p>
                <p class="text-gray-900 font-medium truncate max-w-md">{{ Str::limit($qr->original_url, 50) }}</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3 justify-center">
                
                <button
    type="button"
    onclick="downloadQR('{{ $qr->short_code }}')"
    class="px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 flex items-center gap-2 font-medium">
    <i data-lucide="download" class="w-4 h-4"></i>
    Download QR
</button>


               <a href="{{ url('/q/'.$qr->short_code) }}" target="_blank"

                   class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-medium shadow hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Test Link
                </a>

               <button
    onclick="shareQRCode('{{ url('/q/'.$qr->short_code) }}', '{{ $qr->label ?: 'QR Code' }}')"
    class="px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 flex items-center gap-2 font-medium">
    <i data-lucide="share-2" class="w-4 h-4"></i>
    Share
</button>
            </div>

            {{-- Download Options --}}
            <div class="mt-4 text-sm text-gray-500">
                <p>Downloads as PNG • High resolution • Transparent background</p>
            </div>
        </div>
    </div>
</div>

{{-- ================= ENHANCED EDIT & CUSTOMIZE SECTION ================= --}}
<div id="editForm"
     class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="palette" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Customize QR Code</h2>
                    <p class="text-gray-500 text-sm mt-1">Design and edit your QR code appearance</p>
                </div>
            </div>
            
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg font-medium">
                Live Preview
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ url('/qr-links/'.$qr->_id.'/update') }}"
          class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">

        @csrf

        {{-- LEFT COLUMN - BASIC INFO --}}
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-3">
                    <i data-lucide="edit" class="w-5 h-5 text-indigo-600"></i>
                    Basic Information
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i data-lucide="tag" class="w-4 h-4 text-gray-500"></i>
                            Label (Optional)
                        </label>
                        <input type="text" 
                               name="label"
                               value="{{ $qr->label }}"
                               placeholder="e.g., Marketing Campaign, Event QR"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 text-base">
                        <p class="text-xs text-gray-500 mt-1">Add a descriptive label for easier identification</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i data-lucide="link" class="w-4 h-4 text-gray-500"></i>
                            Destination URL
                        </label>
                        <input type="url" 
                               name="original_url"
                               value="{{ $qr->original_url }}"
                               required
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 text-base">
                        <p class="text-xs text-gray-500 mt-1">The URL this QR code will redirect to when scanned</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i data-lucide="rotate-cw" class="w-4 h-4 text-gray-500"></i>
                            QR Orientation
                        </label>
                        <select x-model="qrRotate"
                                name="qr_rotation"
                                @change="renderQR()"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 text-base">
                            <option value="0">Normal Orientation</option>
                            <option value="90">Rotate 90°</option>
                            <option value="180">Rotate 180°</option>
                            <option value="270">Rotate 270°</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN - DESIGN CUSTOMIZATION --}}
        <div class="space-y-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-3">
                    <i data-lucide="droplets" class="w-5 h-5 text-indigo-600"></i>
                    Design & Colors
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <i data-lucide="palette" class="w-4 h-4 text-gray-500"></i>
                            Color Mode
                        </label>
                        <select x-model="fgType"
                                name="foreground_type"
                                @change="renderQR()"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 text-base">
                            <option value="single">Solid Color</option>
                            <option value="gradient">Gradient Color</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Foreground Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color"
                                       name="foreground_color"
                                       x-model="fgColor"
                                       @input="renderQR()"
                                       class="w-12 h-12 rounded-lg cursor-pointer border border-gray-300">
                                <input type="text"
                                       x-model="fgColor"
                                       @input="renderQR()"
                                       class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 font-mono text-sm">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Background Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color"
                                       name="background_color"
                                       x-model="bgColor"
                                       @input="renderQR()"
                                       class="w-12 h-12 rounded-lg cursor-pointer border border-gray-300">
                                <input type="text"
                                       x-model="bgColor"
                                       @input="renderQR()"
                                       class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 font-mono text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- GRADIENT SETTINGS --}}
                    <div x-show="fgType === 'gradient'" class="space-y-6 pt-6 border-t border-gray-100">
                        <input type="hidden" name="gradient_start" :value="gradStart">
                        <input type="hidden" name="gradient_end" :value="gradEnd">
                        <input type="hidden" name="gradient_dir" :value="gradDir">

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i data-lucide="gradient" class="w-4 h-4 text-gray-500"></i>
                                Gradient Colors
                            </label>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">Start Color</p>
                                    <div class="flex items-center gap-3">
                                        <input type="color" 
                                               x-model="gradStart" 
                                               @input="renderQR()"
                                               class="w-12 h-12 rounded-lg cursor-pointer border border-gray-300">
                                        <input type="text"
                                               x-model="gradStart"
                                               @input="renderQR()"
                                               class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 font-mono text-sm">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">End Color</p>
                                    <div class="flex items-center gap-3">
                                        <input type="color" 
                                               x-model="gradEnd" 
                                               @input="renderQR()"
                                               class="w-12 h-12 rounded-lg cursor-pointer border border-gray-300">
                                        <input type="text"
                                               x-model="gradEnd"
                                               @input="renderQR()"
                                               class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 font-mono text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Gradient Direction</label>
                            <select x-model="gradDir"
                                    @change="renderQR()"
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 text-base">
                                <option value="horizontal">Horizontal →</option>
                                <option value="vertical">Vertical ↓</option>
                                <option value="diagonal">Diagonal ↘</option>
                                <option value="radial">Radial ○</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- HIDDEN FIELDS --}}
        <input type="hidden" name="foreground_type" :value="fgType">
        <input type="hidden" name="foreground_color" :value="fgColor">
        <input type="hidden" name="background_color" :value="bgColor">

        {{-- SAVE BUTTON --}}
        <div class="lg:col-span-2 pt-8 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <i data-lucide="info" class="w-4 h-4 inline mr-2"></i>
                    Changes will update the QR code in real-time
                </div>
                <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-3">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Save All Changes
                </button>
            </div>
        </div>
    </form>
</div>

</div>

{{-- ================= SCRIPTS ================= --}}
<script src="https://unpkg.com/qr-code-styling@1.6.0/lib/qr-code-styling.js"></script>
<script>
// Wait for QRCodeStyling library to load
let qrInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }
    
    // Load QR code styling library
    initializeQR();
});

function initializeQR() {
    // Check if library is loaded
    if (typeof QRCodeStyling === 'undefined') {
        console.error('QRCodeStyling library not loaded');
        setTimeout(initializeQR, 100);
        return;
    }
    
    // Create QR code instance
    const qrUrl = "{{ url('/q/'.$qr->short_code) }}?qr=1";
    
    qrInstance = new QRCodeStyling({
        width: 260,
        height: 260,
        data: qrUrl,
        dotsOptions: {
            color: "#000000",
            type: "square"
        },
        backgroundOptions: {
            color: "#ffffff"
        },
        imageOptions: {
            crossOrigin: "anonymous",
            margin: 10
        }
    });
    
    // Append QR code to container
    const qrContainer = document.getElementById('qrPreview');
    if (qrContainer) {
        qrContainer.innerHTML = '';
        qrInstance.append(qrContainer);
    }
}

// Download QR Code function (same as index page)
function downloadQR(shortCode) {
    if (!qrInstance) {
        alert('QR code is not ready. Please wait a moment.');
        return;
    }
    
    // Show loading state
    showToast('Preparing QR code for download...', 'info');
    
    // Generate filename
    const fileName = `QR-Code-${shortCode}-${Date.now()}`;
    
    // Download as PNG
    qrInstance.getRawData('png').then(blob => {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${fileName}.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        showToast('QR Code downloaded successfully!', 'success');
    }).catch(error => {
        console.error('Download error:', error);
        showToast('Failed to download QR code. Please try again.', 'error');
    });
}

// Share QR Code function - FIXED FOR ALL ENVIRONMENTS
function shareQRCode(url, title) {
    const shareData = {
        title: title || 'QR Code',
        text: 'Scan this QR code to visit: ' + url,
        url: url
    };
    
    // Check if Web Share API is available AND we're on HTTPS
    if (navigator.share && navigator.canShare && navigator.canShare(shareData) && window.location.protocol === 'https:') {
        // HTTPS environment - use Web Share API
        navigator.share(shareData)
            .then(() => {
                showToast('QR code shared successfully!', 'success');
            })
            .catch(error => {
                console.error('Share failed:', error);
                // Fallback to custom share modal
                showCustomShareModal(url, title);
            });
    } else {
        // HTTP/localhost environment OR Web Share not supported
        showCustomShareModal(url, title);
    }
}

// Custom Share Modal for HTTP/localhost
function showCustomShareModal(url, title) {
    // Remove existing modal if any
    const existingModal = document.getElementById('customShareModal');
    if (existingModal) existingModal.remove();
    
    const modal = document.createElement('div');
    modal.id = 'customShareModal';
    modal.className = 'fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-scale-in">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="share-2" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Share QR Code</h3>
                            <p class="text-sm text-gray-600">Copy link or share via apps</p>
                        </div>
                    </div>
                    <button onclick="closeCustomShareModal()" 
                            class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-xl transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 mb-2">QR Code Link</p>
                    <div class="flex items-center gap-2 bg-gray-50 px-4 py-3 rounded-xl border border-gray-200">
                        <div class="flex-1 truncate text-gray-800 font-medium text-sm">${url}</div>
                        <button onclick="copyToClipboard('${url}'); closeCustomShareModal();" 
                                class="text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition-colors flex-shrink-0"
                                title="Copy">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Copy this link to share the QR code</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="shareViaWhatsApp('${url}', '${title}'); closeCustomShareModal();"
                            class="flex items-center justify-center gap-2 px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl border border-green-200 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        </svg>
                        <span class="font-medium">WhatsApp</span>
                    </button>
                    
                    <button onclick="shareViaEmail('${url}', '${title}'); closeCustomShareModal();"
                            class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl border border-blue-200 transition-colors">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                        <span class="font-medium">Email</span>
                    </button>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600 mb-3">Quick actions:</p>
                    <div class="flex gap-2">
                        <button onclick="downloadQR('{{ $qr->short_code }}'); closeCustomShareModal();"
                                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 text-indigo-700 rounded-xl border border-indigo-200 transition-colors font-medium">
                            <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                            Download QR
                        </button>
                        <a href="${url}" target="_blank"
                           class="flex-1 px-4 py-2.5 bg-gradient-to-r from-emerald-50 to-green-50 hover:from-emerald-100 hover:to-green-100 text-emerald-700 rounded-xl border border-emerald-200 transition-colors font-medium text-center">
                            <i data-lucide="external-link" class="w-4 h-4 inline mr-2"></i>
                            Open Link
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Initialize icons
    if (window.lucide) {
        lucide.createIcons();
    }
    
    // Add animation style if not exists
    if (!document.getElementById('modal-styles')) {
        const style = document.createElement('style');
        style.id = 'modal-styles';
        style.textContent = `
            @keyframes scale-in {
                from { transform: scale(0.95); opacity: 0; }
                to { transform: scale(1); opacity: 1; }
            }
            .animate-scale-in { animation: scale-in 0.2s ease-out; }
        `;
        document.head.appendChild(style);
    }
}

function closeCustomShareModal() {
    const modal = document.getElementById('customShareModal');
    if (modal) {
        modal.classList.add('opacity-0');
        setTimeout(() => modal.remove(), 200);
    }
}

// Platform-specific sharing functions
function shareViaWhatsApp(url, title) {
    const text = encodeURIComponent(`${title}: ${url}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
    showToast('Opening WhatsApp...', 'info');
}

function shareViaEmail(url, title) {
    const subject = encodeURIComponent(title);
    const body = encodeURIComponent(`Scan this QR code or visit: ${url}`);
    window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
    showToast('Opening email...', 'info');
}

// PERFECT TOAST FUNCTION - VISIBLE MESSAGES
function showToast(message, type = 'info') {
    const colors = {
        success: 'bg-gradient-to-r from-emerald-500 to-green-600',
        error: 'bg-gradient-to-r from-rose-500 to-red-600',
        info: 'bg-gradient-to-r from-blue-500 to-cyan-600'
    };
    
    const icons = {
        success: 'check-circle',
        error: 'alert-circle',
        info: 'info'
    };

    const toast = document.createElement('div');
    toast.className = `fixed top-4 sm:top-6 right-4 sm:right-6 z-[99999] px-4 py-3 sm:px-5 sm:py-4 rounded-xl shadow-2xl transform transition-all duration-300 translate-x-full ${colors[type]}`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/30 backdrop-blur-sm rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="${icons[type]}" class="w-4 h-4 sm:w-5 sm:h-5 text-white"></i>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-sm sm:text-base truncate text-white">${type.charAt(0).toUpperCase() + type.slice(1)}</p>
                <p class="text-xs sm:text-sm mt-0.5 line-clamp-2 text-white/95">${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 10);
    
    // Initialize icon
    if (window.lucide) {
        const icon = toast.querySelector('i');
        lucide.createIcons({ icons: [icon] });
    }
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Copy to clipboard function with perfect toast
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Link copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy: ', err);
        showToast('Failed to copy. Please try again.', 'error');
    });
}

// Alpine.js QR Designer Component
function qrDesigner() {
    return {
        fgType: "{{ $qr->foreground_type ?? 'single' }}",
        fgColor: "{{ $qr->foreground_color ?? '#000000' }}",
        bgColor: "{{ $qr->background_color ?? '#ffffff' }}",
        gradStart: "{{ $qr->gradient_start ?? '#000000' }}",
        gradEnd: "{{ $qr->gradient_end ?? '#ff0000' }}",
        gradDir: "{{ $qr->gradient_dir ?? 'horizontal' }}",
        qrRotate: {{ $qr->qr_rotation ?? 0 }},
        
        init() {
            // Initialize after a short delay
            setTimeout(() => {
                this.renderQR();
            }, 500);
        },
        
        renderQR() {
            if (!qrInstance || typeof QRCodeStyling === 'undefined') {
                console.warn('QR instance not ready');
                return;
            }
            
            const gradient = this.fgType === 'gradient' ? {
                type: this.gradDir === 'radial' ? 'radial' : 'linear',
                rotation: this.gradDir === 'vertical' ? 90 : this.gradDir === 'diagonal' ? 45 : 0,
                colorStops: [
                    { offset: 0, color: this.gradStart },
                    { offset: 1, color: this.gradEnd }
                ]
            } : null;
            
            // Update QR instance with new options
            qrInstance.update({
                dotsOptions: {
                    color: this.fgType === 'single' ? this.fgColor : undefined,
                    gradient: gradient
                },
                backgroundOptions: {
                    color: this.bgColor
                }
            });
        }
    };
}
</script>

<style>
/* Custom styles for enhanced UI */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-pulse {
    animation: pulse 2s infinite;
}

/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Card hover effects */
.bg-white {
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}

.bg-white:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

/* Button interactions */
button, a {
    transition: all 0.2s ease;
}

/* Input focus states */
input:focus, select:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Color input styling */
input[type="color"] {
    -webkit-appearance: none;
    appearance: none;
    border: none;
    cursor: pointer;
}

input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}

input[type="color"]::-webkit-color-swatch {
    border: none;
    border-radius: 8px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c7d2fe;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a5b4fc;
}

/* Toast visibility fix */
.fixed.z-\[99999\] {
    z-index: 99999 !important;
}

.bg-gradient-to-r.from-emerald-500.to-green-600 {
    background-image: linear-gradient(to right, #10b981, #22c55e) !important;
}

.bg-gradient-to-r.from-rose-500.to-red-600 {
    background-image: linear-gradient(to right, #f43f5e, #dc2626) !important;
}

.bg-gradient-to-r.from-blue-500.to-cyan-600 {
    background-image: linear-gradient(to right, #3b82f6, #0891b2) !important;
}

.text-white {
    color: white !important;
}

.text-white\\/95 {
    color: rgba(255, 255, 255, 0.95) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-2, .grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    .space-y-8 {
        margin-bottom: 2rem;
    }
}
</style>

@endsection