@extends('layouts.index')
@section('title','QR Codes')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">QR Codes</h1>
    <a href="{{ route('qr.builder.create') }}"
       class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg">
        Create QR
    </a>
</div>

@if($qrCodes->count() === 0)
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
    <h2 class="text-2xl font-semibold text-gray-800">
        You haven't created any QR Codes yet
    </h2>
    <p class="text-gray-500 mt-2">
        Create your first QR code to get started.
    </p>
    <a href="{{ route('qr.builder.create') }}"
       class="mt-6 px-6 py-3 bg-purple-600 text-white rounded-xl">
        ➕ Create QR
    </a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
@foreach($qrCodes as $qr)
<div class="bg-white rounded-xl border p-4 relative group">
    <!-- QR Code with Logo -->
    <div class="relative">
       <img
  src="{{ $qr->qr_image_url ? Storage::disk('public')->url($qr->qr_image_url) : asset('images/qr-placeholder.png') }}"
  alt="{{ $qr->title }}"
  class="w-full h-48 object-contain mb-3 rounded-lg border"
/>

        <!-- Logo Overlay (if exists) -->
        @if($qr->logo_url)
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <img 
                src="{{ asset('storage/'.$qr->logo_url) }}" 
                alt="Logo"
                class="w-10 h-10 object-contain bg-white p-1 rounded-full shadow-md"
            />
        </div>
        @endif
        
        <!-- Design Template Badge -->
        @if($qr->design_template && $qr->design_template != 'default')
        <div class="absolute top-2 right-2">
            <span class="px-2 py-1 text-xs rounded bg-gray-800 text-white">
                {{ ucfirst($qr->design_template) }}
            </span>
        </div>
        @endif
    </div>

    <h3 class="font-semibold text-gray-800">{{ $qr->title }}</h3>
    <p class="text-xs text-gray-500">{{ $qr->scans }} scans</p>
    
    <div class="flex gap-2 mt-4">
        <button
            type="button"
            onclick="openQrDetails(this)"
            data-qr-id="{{ $qr->_id }}"
            data-title="{{ $qr->title }}"
            data-type="{{ $qr->qr_type }}"
            data-mode="{{ $qr->qr_mode }}"
            data-scans="{{ $qr->scans }}"
            data-image="{{ $qr->qr_image_url ? Storage::disk('public')->url($qr->qr_image_url) : '' }}"
data-download="{{ $qr->qr_image_url ? Storage::disk('public')->url($qr->qr_image_url) : '' }}"
            data-status="{{ $qr->is_active ? 'Active' : 'Inactive' }}"
            data-created="{{ $qr->created_at->format('M d, Y') }}"
            data-short-url="{{ $qr->short_url }}"
            data-qr-data="{{ e($qr->qr_mode === 'dynamic' ? url('/qr/'.$qr->short_url) : $qr->qr_data) }}"
            
            data-logo-url="{{ $qr->logo_url ? asset('storage/'.$qr->logo_url) : '' }}"
            data-design-template="{{ $qr->design_template ?: 'default' }}"
            data-design-data="{{ e($qr->design ? json_encode($qr->design) : '') }}"
            
            data-edit-url="{{ route('qr.edit', $qr->_id) }}"
            
            class="flex-1 text-center px-3 py-1.5 text-sm rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-50">
            👁 View
        </button>

        <a href="{{ route('qr.edit', $qr->_id) }}"
           class="flex-1 text-center px-3 py-1.5 text-sm rounded-lg border border-indigo-500 text-indigo-600 hover:bg-indigo-50">
            ✏️ Edit
        </a>
        
        <form method="POST"
              action="{{ route('qr.delete', $qr->_id) }}"
              onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="flex-1 text-center px-3 py-1.5 text-sm rounded-lg border border-red-500 text-red-600 hover:bg-red-50">
                🗑 Delete
            </button>
        </form>
    </div>
</div>
@endforeach
</div>
@endif

<!-- QR DETAILS MODAL -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">QR Details</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            
            <!-- Modal Content -->
            <div class="space-y-6">
                <!-- QR Image -->
                <div class="flex justify-center">
                    <img id="modalQrImage" src="" alt="QR Code" class="w-64 h-64 object-contain border rounded-lg">
                </div>
                
                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Title</label>
                        <p id="modalTitle" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">QR Type</label>
                        <p id="modalType" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">QR Mode</label>
                        <p id="modalMode" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Total Scans</label>
                        <p id="modalScans" class="text-lg font-semibold text-gray-800"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span id="modalStatus" class="px-3 py-1 text-xs font-semibold rounded-full"></span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Created Date</label>
                        <p id="modalCreated" class="text-sm text-gray-600"></p>
                    </div>
                </div>
                
                <!-- Short URL (for dynamic QR) -->
                <div id="shortUrlSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Short URL</label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="modalShortUrl" readonly 
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                        <button onclick="copyShortUrl()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Copy
                        </button>
                    </div>
                </div>
                
                <!-- QR Data -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">QR Data/Content</label>
                    <textarea id="modalData" rows="3" readonly 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-sm"></textarea>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <a id="modalEditLink" href="#"
                       class="flex-1 text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        ✏️ Edit QR
                    </a>
                    <a id="modalDownloadLink" href="#" target="_blank"
                       class="flex-1 text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        ⬇️ Download QR
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Function to open modal with QR details
// Function to open modal with QR details - UPDATED VERSION
function openQrDetails(button) {
    // 🔑 short code
    const shortUrl = button.getAttribute('data-short-url');

    // 🖼️ QR IMAGE URL (SAFE FOR LOCAL + LIVE)
    const fullImageUrl = button.getAttribute('data-image');

    // 🔥 NEW: Logo and Design data
    const logoUrl = button.getAttribute('data-logo-url');
    const designTemplate = button.getAttribute('data-design-template');
    const designDataStr = button.getAttribute('data-design-data');

    // OTHER DATA
    const qrId = button.getAttribute('data-qr-id');
    const title = button.getAttribute('data-title');
    const type = button.getAttribute('data-type');
    const mode = button.getAttribute('data-mode');
    const scans = button.getAttribute('data-scans');
    const status = button.getAttribute('data-status');
    const created = button.getAttribute('data-created');
    const qrData = button.getAttribute('data-qr-data');
    const editUrl = button.getAttribute('data-edit-url');

    // SET IMAGE + DOWNLOAD
    const qrImage = document.getElementById('modalQrImage');
    qrImage.src = fullImageUrl;
    
    // 🔥 Clear any previous logo overlay and design badge
    const modalPreview = qrImage.parentElement;
    const existingLogo = modalPreview.querySelector('.logo-overlay');
    const existingDesignBadge = modalPreview.querySelector('.design-badge');
    if (existingLogo) existingLogo.remove();
    if (existingDesignBadge) existingDesignBadge.remove();

    // 🔥 Add logo overlay if logo exists
    if (logoUrl && logoUrl.trim() !== '') {
        modalPreview.classList.add('relative');
        const logoOverlay = document.createElement('div');
        logoOverlay.className = 'logo-overlay absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10';
        logoOverlay.innerHTML = `
            <div class="bg-white p-1.5 rounded-full shadow-lg">
                <img src="${logoUrl}" alt="Logo" class="w-12 h-12 object-contain">
            </div>
        `;
        modalPreview.appendChild(logoOverlay);
    }

    // 🔥 Add design template badge
    if (designTemplate && designTemplate !== 'default') {
        const designBadge = document.createElement('div');
        designBadge.className = 'design-badge mt-3 text-center';
        designBadge.innerHTML = `
            <span class="inline-block px-3 py-1 text-xs bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full font-medium">
                ${designTemplate.charAt(0).toUpperCase() + designTemplate.slice(1)} Design
            </span>
        `;
        modalPreview.parentNode.insertBefore(designBadge, modalPreview.nextSibling);
    }

    document.getElementById('modalDownloadLink').href = button.getAttribute('data-download');
    document.getElementById('modalTitle').textContent = title;

    // TEXT DATA
    document.getElementById('modalType').textContent = type;
    document.getElementById('modalMode').textContent = mode;
    document.getElementById('modalScans').textContent = scans;
    document.getElementById('modalCreated').textContent = created;
    document.getElementById('modalData').value = qrData;
    document.getElementById('modalEditLink').href = editUrl;

    // STATUS
    const statusElem = document.getElementById('modalStatus');
    if (status === 'Active') {
        statusElem.textContent = 'Active';
        statusElem.className =
            'px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
    } else {
        statusElem.textContent = 'Inactive';
        statusElem.className =
            'px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
    }

    // SHORT URL (only dynamic)
    const shortUrlSection = document.getElementById('shortUrlSection');
    if (mode === 'dynamic' && shortUrl) {
        shortUrlSection.classList.remove('hidden');
        document.getElementById('modalShortUrl').value =
            `${window.location.origin}/qr/${shortUrl}`;
    } else {
        shortUrlSection.classList.add('hidden');
    }

    // 🔥 Add design details if available
    if (designDataStr && designDataStr.trim() !== '' && designDataStr !== 'null') {
        try {
            const designData = JSON.parse(designDataStr);
            
            // Optional: Display design details in modal
            const designDetailsElem = document.getElementById('modalDesignDetails');
            if (!designDetailsElem) {
                // Create a new section for design details
                const detailsGrid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.gap-4');
                if (detailsGrid) {
                    const designSection = document.createElement('div');
                    designSection.id = 'modalDesignDetails';
                    designSection.className = 'md:col-span-2 border-t pt-4 mt-4';
                    designSection.innerHTML = `
                        <label class="block text-sm font-medium text-gray-500 mb-2">Design Details</label>
                        <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg text-sm">
                            <div class="grid grid-cols-2 gap-2">
                                <div>Template: <span class="font-medium">${designTemplate}</span></div>
                                <div>Dot Style: <span class="font-medium">${designData.dot_style || 'default'}</span></div>
                                <div>Eye Style: <span class="font-medium">${designData.eye_style || 'default'}</span></div>
                                <div>Color Type: <span class="font-medium">${designData.color_type || 'single'}</span></div>
                            </div>
                        </div>
                    `;
                    detailsGrid.appendChild(designSection);
                }
            }
        } catch (e) {
            console.error('Error parsing design data:', e);
        }
    } else {
        // Remove design details section if exists
        const existingDesignSection = document.getElementById('modalDesignDetails');
        if (existingDesignSection) existingDesignSection.remove();
    }

    // SHOW MODAL
    document.getElementById('qrModal').classList.remove('hidden');
}

// Function to close modal - UPDATED
function closeModal() {
    // 🔥 Clean up dynamic elements
    const modal = document.getElementById('qrModal');
    const qrImage = document.getElementById('modalQrImage');
    const modalPreview = qrImage.parentElement;
    
    // Remove logo overlay
    const existingLogo = modalPreview.querySelector('.logo-overlay');
    if (existingLogo) existingLogo.remove();
    
    // Remove design badge
    const existingDesignBadge = modalPreview.querySelector('.design-badge');
    if (existingDesignBadge) existingDesignBadge.remove();
    
    // Remove design details section
    const designSection = document.getElementById('modalDesignDetails');
    if (designSection) designSection.remove();
    
    // Hide modal
    modal.classList.add('hidden');
}
// Function to copy short URL
function copyShortUrl() {
    const input = document.getElementById('modalShortUrl');
    input.select();
    input.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(input.value)
        .then(() => {
            // Show success message
            const btn = document.querySelector('[onclick="copyShortUrl()"]');
            const originalText = btn.textContent;
            btn.textContent = 'Copied!';
            btn.classList.remove('bg-blue-600');
            btn.classList.add('bg-green-600');
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-blue-600');
            }, 2000);
        })
        .catch(err => {
            console.error('Failed to copy:', err);
            alert('Failed to copy URL');
        });
}

// Function to close modal

// Close modal when clicking outside
document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target.id === 'qrModal') {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('qrModal').classList.contains('hidden')) {
        closeModal();
    }
});
</script>
<style>
/* Logo overlay styles */
.logo-overlay {
    animation: fadeIn 0.3s ease-in;
}

.logo-overlay img {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

/* Design badge animation */
.design-badge {
    animation: slideUp 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
    to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* QR container styling */
#qrPreview, #modalQrImage {
    background: linear-gradient(45deg, #f9fafb 25%, transparent 25%),
                linear-gradient(-45deg, #f9fafb 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #f9fafb 75%),
                linear-gradient(-45deg, transparent 75%, #f9fafb 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
}
</style>
@endsection