{{-- resources/views/bio/tabs/social-links.blade.php --}}

<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Social Links</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage your social media links</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 space-y-5 sm:space-y-6">
        <div class="bg-gray-50 rounded-lg p-4 sm:p-5">
            <h3 class="text-sm sm:text-base font-medium text-gray-900 mb-4">Add Social Link</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        Platform
                    </label>
                    <div class="relative">
                        <select id="socialPlatform" 
                                class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent appearance-none bg-white pr-10">
                            <option value="">Select a platform</option>
@php
    $socials = config('socials');
    
    $socialLinks = [];
    
    if (isset($editingBio->social_links)) {
        $sl = $editingBio->social_links;
        if (is_string($sl)) {
            try {
                $decoded = json_decode($sl, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $socialLinks = $decoded;
                }
            } catch (\Exception $e) {
                $socialLinks = [];
            }
        } elseif (is_array($sl)) {
            $socialLinks = $sl;
        } elseif (is_object($sl)) {
            $socialLinks = (array) $sl;
        }
    }
    
    if (!isset($socialLinks['items'])) {
        $socialLinks['items'] = [];
    }
    
    $existingPlatforms = collect($socialLinks['items'])
        ->pluck('platform')
        ->toArray();
@endphp
                            @foreach($socials as $key => $platform)
                                <option value="{{ $key }}" 
                                        @if(in_array($key, $existingPlatforms)) disabled @endif>
                                    {{ in_array($key, $existingPlatforms) ? "✓ " . $platform['label'] . " (Added)" : $platform['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        Link URL
                    </label>
                    <input type="url" 
                           id="socialUrl"
                           class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="https://example.com/your-profile">
                </div>
                <button type="button"
                        onclick="addSocialLink()"
                        id="addSocialBtn"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 sm:py-3.5 rounded-lg font-medium text-sm sm:text-base flex items-center justify-center gap-2 transition duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Social Link
                </button>
            </div>
        </div>
        <div>
            <h3 class="text-sm sm:text-base font-medium text-gray-900 mb-4">Your Social Links</h3>
            
            <div id="socialLinksContainer" class="space-y-3">
                @php
                    $socialLinks = $editingBio->social_links['items'] ?? [];
                @endphp
                
                @forelse($socialLinks as $link)
                <div class="social-link-item bg-white border border-gray-200 rounded-lg p-4" data-platform="{{ $link['platform'] }}">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="cursor-move text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </div>
                        @php
                            $platformConfig = config('socials.' . $link['platform']) ?? config('socials.custom');
                        @endphp
                        <div class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg" 
                             style="background-color: {{ $platformConfig['color'] }}20; border: 1px solid {{ $platformConfig['color'] }}30;">
                            @if($platformConfig['icon'] === 'link' || $platformConfig['icon'] === 'globe')
                                <svg class="w-5 h-5" style="color: {{ $platformConfig['color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 100-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <i class="fab fa-{{ $platformConfig['icon'] }} text-lg" style="color: {{ $platformConfig['color'] }}"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <strong class="text-xs sm:text-sm font-medium text-gray-900">
                                    {{ $platformConfig['label'] }}
                                </strong>
                                <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Connected
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 truncate mt-1">
                                {{ $link['url'] }}
                            </div>
                        </div>
                        <button type="button"
                                onclick="removeSocialLink('{{ $link['platform'] }}')"
                                class="text-gray-400 hover:text-red-600 transition duration-200 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div id="socialLinksEmpty" class="text-center py-8 border-2 border-dashed border-gray-200 rounded-lg">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <h4 class="text-sm sm:text-base font-medium text-gray-900 mb-2">No social links yet</h4>
                    <p class="text-xs sm:text-sm text-gray-500">Add your first social link above</p>
                </div>
                @endforelse
            </div>
        </div>
        <div id="socialDisplayForm" method="POST" action="{{ route('bio.social.display.save', $editingBio->_id) }}">
            @csrf
            <div class="border-t pt-5 sm:pt-6 space-y-4">
                <h3 class="text-sm sm:text-base font-medium text-gray-900">Display Settings</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Position
                        </label>
                        <select name="position"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="top" {{ ($editingBio->social_links['position'] ?? 'top') === 'top' ? 'selected' : '' }}>Top</option>
                            <option value="bottom" {{ ($editingBio->social_links['position'] ?? 'top') === 'bottom' ? 'selected' : '' }}>Bottom</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                            Style
                        </label>
                        <select name="style"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                            <option value="icon_only" {{ ($editingBio->social_display_style ?? 'icon_only') === 'icon_only' ? 'selected' : '' }}>Icon Only</option>
                            <option value="icon_bg" {{ ($editingBio->social_display_style ?? 'icon_only') === 'icon_bg' ? 'selected' : '' }}>Icon with Background</option>
                        </select>
                    </div>
                </div>
                <button type="submit"
                        class="w-full bg-black hover:bg-gray-900 text-white py-3 sm:py-3.5 rounded-lg font-medium text-sm sm:text-base transition duration-200 hover:shadow-md">
                    Save Display Settings
                </button>
            </div>
</div>
    </div>

</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
const platformData = @json(config('socials'));
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
function showToast(message, type = 'info') {
    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800'
    };

    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg border shadow-lg transform transition-all duration-300 translate-y-0 opacity-100 ${colors[type]}`;
    toast.innerHTML = `
        <div class="flex items-center gap-2">
            ${type === 'success' ? 
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' :
                type === 'error' ?
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' :
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            }
            <span class="text-sm">${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateY(-10px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Validate URL
function validateUrl(url) {
    try {
        new URL(url);
        return true;
    } catch (error) {
        return false;
    }
}

// Update social links display
function updateSocialLinksDisplay() {
    const container = document.getElementById('socialLinksContainer');
    const emptyState = document.getElementById('socialLinksEmpty');
    
    // Show/hide empty state
    if (container.children.length === 0 && emptyState) {
        emptyState.classList.remove('hidden');
    } else if (emptyState) {
        emptyState.classList.add('hidden');
    }
}

// Add social link via AJAX
async function addSocialLink() {
    const platformSelect = document.getElementById('socialPlatform');
    const urlInput = document.getElementById('socialUrl');
    const addButton = document.getElementById('addSocialBtn');

    const platform = platformSelect.value;
    const url = urlInput.value.trim();

    if (!platform) {
        showToast('Please select a platform', 'error');
        return;
    }

    if (!url || !validateUrl(url)) {
        showToast('Please enter a valid URL', 'error');
        return;
    }

    const originalText = addButton.innerHTML;
    addButton.innerHTML = 'Adding...';
    addButton.disabled = true;

    try {
        const response = await fetch("{{ route('bio.social.add', $editingBio->_id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ platform, url })
        });

        const data = await response.json();

      if (response.ok && data.ok) {
    showToast('Social link added successfully!', 'success');

    // 1️⃣ add to list
    addSocialLinkToList(platform, url);

    // 2️⃣ add to live preview
    renderSocialInPreview(platform, url);

    // ✅ Update platform dropdown options
    updatePlatformOptions();

    platformSelect.value = '';
    urlInput.value = '';
}
 else {
            showToast(data.message || 'Failed to add link', 'error');
        }

    } catch (err) {
        console.error(err);
        showToast('Network error', 'error');
    } finally {
        addButton.innerHTML = originalText;
        addButton.disabled = false;
    }
}


// ✅ Function to dynamically update platform dropdown
function updatePlatformOptions() {
    const platformSelect = document.getElementById('socialPlatform');
    if (!platformSelect) return;
    
    // Get currently added platforms from DOM
    const addedPlatforms = [];
    const linkItems = document.querySelectorAll('.social-link-item[data-platform]');
    
    linkItems.forEach(item => {
        addedPlatforms.push(item.dataset.platform);
    });
    
    // Update each option based on current state
    platformSelect.querySelectorAll('option').forEach(option => {
        const platformValue = option.value;
        
        if (platformValue && platformValue !== '') {
            const platformConfig = platformData[platformValue];
            
            if (platformConfig) {
                const isAdded = addedPlatforms.includes(platformValue);
                
                option.disabled = isAdded;
                
                if (isAdded) {
                    option.textContent = `✓ ${platformConfig.label} (Added)`;
                } else {
                    option.textContent = platformConfig.label;
                }
            }
        }
    });
}

// ✅ Remove social link via AJAX
async function removeSocialLink(platform) {
    if (!confirm(`Are you sure you want to remove this social link?`)) {
        return;
    }
    
    try {
        const response = await fetch("{{ route('bio.social.delete', $editingBio->_id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ 
                platform: platform 
            })
        });
        
        const data = await response.json();
        
        if (response.ok && data.ok) {
            showToast('Social link removed', 'success');
            
            // Remove from DOM
            const item = document.querySelector(`.social-link-item[data-platform="${platform}"]`);
            if (item) {
                item.remove();
            }
            
            // ✅ Update platform dropdown options
            updatePlatformOptions();
            
            // ✅ Update localStorage when removing
            saveSocialLinksToLocalStorage();
            
            // Update display
            updateSocialLinksDisplay();
            
        } else {
            showToast(data.message || 'Failed to remove link', 'error');
        }
    } catch (error) {
        console.error('Error removing social link:', error);
        showToast('Network error. Please try again.', 'error');
    }
}

// ✅ Function to add social link to list
function addSocialLinkToList(platform, url) {
    const container = document.getElementById('socialLinksContainer');
    const empty = document.getElementById('socialLinksEmpty');
    if (empty) empty.remove();

    const data = platformData[platform];

    const div = document.createElement('div');
    div.className = 'social-link-item bg-white border rounded-lg p-4';
    div.dataset.platform = platform;

    div.innerHTML = `
        <div class="flex items-center gap-4">
            <div class="cursor-move text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                 style="background:${data.color}20; border: 1px solid ${data.color}30">
                <i class="fab fa-${data.icon}" style="color:${data.color}"></i>
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <strong class="text-sm font-medium">${data.label}</strong>
                    <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                        Connected
                    </span>
                </div>
                <div class="text-xs text-gray-500 truncate">${url}</div>
            </div>

            <button onclick="removeSocialLink('${platform}')"
                    class="text-gray-400 hover:text-red-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

    container.appendChild(div);
    
    // ✅ Save to localStorage (YEH LINE ADD KARO)
    saveSocialLinksToLocalStorage();
    
    // Re-initialize drag and drop for new item
    initializeDragAndDrop();
}

// ✅ Save current social links to localStorage
function saveSocialLinksToLocalStorage() {
    const socialLinks = [];
    const items = document.querySelectorAll('.social-link-item');
    
    items.forEach(item => {
        const platform = item.dataset.platform;
        const url = item.querySelector('.text-xs.text-gray-500')?.textContent || '';
        if (platform && url) {
            socialLinks.push({ platform, url });
        }
    });
    
localStorage.setItem(
    'socialLinksPreview_' + window.CURRENT_BIO_ID,
    JSON.stringify(socialLinks)
);
}

// ✅ Load social links from localStorage on page load
function loadSocialLinksFromLocalStorage() {
    const saved = localStorage.getItem(
    'socialLinksPreview_' + window.CURRENT_BIO_ID
);

    if (saved) {
        try {
            const socialLinks = JSON.parse(saved);
            const preview = document.getElementById('livePreviewSocials');
            if (preview) {
                preview.innerHTML = '';
                socialLinks.forEach(link => {
                    renderSocialInPreview(link.platform, link.url);
                });
            }
        } catch (e) {
            console.error('Error loading saved social links:', e);
        }
    }
}

// ✅ Function to render social in preview with current style
function renderSocialInPreview(platform, url) {
    try {
        const preview = document.getElementById('livePreviewSocials');
        if (!preview) {
            console.warn('Preview container #livePreviewSocials not found');
            return;
        }

        // Get current display style
        const styleSelect = document.querySelector('select[name="style"]');
        const currentStyle = styleSelect ? styleSelect.value : 'icon_bg';
        
        // Remove existing if already added
        const existing = preview.querySelector(`[data-platform="${platform}"]`);
        if (existing) existing.remove();

        const data = platformData[platform] || {
            icon: 'link',
            color: '#6b7280',
            label: platform
        };

        const btn = document.createElement('a');
        btn.href = url;
        btn.target = '_blank';
        btn.dataset.platform = platform;
        btn.className = 'inline-flex items-center justify-center m-1 hover:scale-110 transition-transform duration-200';
        
        // ✅ Apply different styles based on setting
        if (currentStyle === 'icon_only') {
            // Icon Only - NO background, NO border
            btn.style.background = 'transparent';
            btn.style.border = 'none';
            btn.style.boxShadow = 'none';
            btn.style.width = '40px';
            btn.style.height = '40px';
        } else {
            // Icon with Background - with background and border
            btn.className += ' rounded-full shadow-sm';
            const color = data.color || '#6b7280';
            btn.style.background = color + '20';
            btn.style.border = `1px solid ${color}40`;
            btn.style.width = '44px';
            btn.style.height = '44px';
        }

        // Create icon
        const icon = document.createElement('i');
        const iconName = data.icon || 'link';
        const iconColor = data.color || '#374151';
        
        if (iconName === 'link' || iconName === 'globe') {
            icon.innerHTML = `<svg width="${currentStyle === 'icon_only' ? '22' : '20'}" height="${currentStyle === 'icon_only' ? '22' : '20'}" viewBox="0 0 20 20" fill="${iconColor}">
                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 100-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z"/>
            </svg>`;
        } else {
            icon.className = `fab fa-${iconName}`;
            icon.style.color = iconColor;
            icon.style.fontSize = currentStyle === 'icon_only' ? '20px' : '18px';
        }
        
        btn.appendChild(icon);
        preview.appendChild(btn);
        
    } catch (error) {
        console.error('Error in renderSocialInPreview:', error);
    }
}

// ✅ Function to apply position to preview
function applyPreviewPosition() {
    const positionSelect = document.querySelector('select[name="position"]');
    const currentPosition = positionSelect ? positionSelect.value : 'top';
    
    const previewContainer = document.getElementById('livePreviewSocials');
    if (!previewContainer) return;
    
    // Remove existing position classes
    previewContainer.classList.remove('preview-top', 'preview-bottom');
    
    // Add new position class
    previewContainer.classList.add(`preview-${currentPosition}`);
    
    // Also update parent container classes if needed
    const parentContainer = previewContainer.closest('.preview-section');
    if (parentContainer) {
        parentContainer.classList.remove('socials-top', 'socials-bottom');
        parentContainer.classList.add(`socials-${currentPosition}`);
    }
}

// ✅ Function to refresh all social previews with current style
function refreshAllSocialPreviews() {
    const preview = document.getElementById('livePreviewSocials');
    if (!preview) return;
    
    // Store current icons
    const currentIcons = [];
    preview.querySelectorAll('a[data-platform]').forEach(btn => {
        currentIcons.push({
            platform: btn.dataset.platform,
            url: btn.href
        });
    });
    
    // Clear and re-render with new style
    preview.innerHTML = '';
    currentIcons.forEach(icon => {
        renderSocialInPreview(icon.platform, icon.url);
    });
}

function initializeDragAndDrop() {
    const container = document.getElementById('socialLinksContainer');
    
    if (!container) return;
    
    // Simple drag and drop implementation
    let draggedItem = null;
    
    container.querySelectorAll('.cursor-move').forEach(handle => {
        handle.setAttribute('draggable', 'true');
        
        handle.addEventListener('dragstart', (e) => {
            draggedItem = e.target.closest('.social-link-item');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', '');
            
            // Add visual feedback
            draggedItem.classList.add('opacity-50');
        });
        
        handle.addEventListener('dragend', () => {
            if (draggedItem) {
                draggedItem.classList.remove('opacity-50');
                draggedItem = null;
            }
        });
    });
    
    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    });
    
    container.addEventListener('drop', (e) => {
        e.preventDefault();
        if (!draggedItem) return;
        
        const afterElement = getDragAfterElement(container, e.clientY);
        
        if (afterElement) {
            container.insertBefore(draggedItem, afterElement);
        } else {
            container.appendChild(draggedItem);
        }
        
        showToast('Order updated. Save to keep changes.', 'info');
    });
}

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.social-link-item:not(.opacity-50)')];
    
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Load saved social links from localStorage FIRST
    loadSocialLinksFromLocalStorage();
    
    // Auto-focus URL input when platform is selected
    const platformSelect = document.getElementById('socialPlatform');
    const urlInput = document.getElementById('socialUrl');
    
    if (platformSelect && urlInput) {
        platformSelect.addEventListener('change', function() {
            if (this.value) {
                setTimeout(() => urlInput.focus(), 100);
            }
        });
    }
    
    // Enter key to add social link
    if (urlInput) {
        urlInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSocialLink();
            }
        });
    }
    
    // ✅ Real-time style change for preview
    const styleSelect = document.querySelector('select[name="style"]');
    if (styleSelect) {
        styleSelect.addEventListener('change', function() {
            refreshAllSocialPreviews();
            saveSocialLinksToLocalStorage(); // ✅ Save style preference
        });
    }
    
    // ✅ Real-time position change for preview
    const positionSelect = document.querySelector('select[name="position"]');
    if (positionSelect) {
        positionSelect.addEventListener('change', function() {
            applyPreviewPosition();
            saveSocialLinksToLocalStorage(); // ✅ Save position preference
        });
    }
    
    // Initialize drag and drop
    initializeDragAndDrop();
    
    // ✅ Update platform options on initial load
    updatePlatformOptions();
    
    // ✅ Apply initial position
    applyPreviewPosition();
    
    // Form submission for display settings
    const displayForm = document.getElementById('socialDisplayForm');
    if (displayForm) {
        displayForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = `
                <svg class="w-4 h-4 animate-spin inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Saving...
            `;
            submitButton.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Display settings saved!', 'success');
                    
                    // ✅ Refresh all social icons in preview with new settings
                    refreshAllSocialPreviews();
                    
                    // ✅ Apply position
                    applyPreviewPosition();
                    
                    // ✅ Save to localStorage after saving settings
                    saveSocialLinksToLocalStorage();
                    
                } else {
                    showToast(data.message || 'Failed to save settings', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving display settings:', error);
                showToast('Network error. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }
});

// Initial render of social icons in preview (Database backup)
@foreach($editingBio->social_links['items'] ?? [] as $link)
    renderSocialInPreview('{{ $link['platform'] }}', '{{ $link['url'] }}');
@endforeach

// Add CSS for disabled select options
const style = document.createElement('style');
style.textContent = `
    select option:disabled {
        color: #9ca3af;
        background-color: #f3f4f6;
    }
    select option:not(:disabled):hover {
        background-color: #f3f4f6;
    }
    
    /* Preview positioning */
    .preview-top {
        order: 1;
        margin-bottom: 1rem;
    }
    
    .preview-bottom {
        order: 999;
        margin-top: 1rem;
    }
`;
document.head.appendChild(style);
</script>