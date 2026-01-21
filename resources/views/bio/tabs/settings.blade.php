{{-- resources/views/bio/tabs/settings.blade.php --}}
{{-- SETTINGS TAB --}}
<div class="space-y-8" id="settingsTab">

    {{-- ================= SEO SECTION ================= --}}
    <div class="bg-white rounded-xl border shadow-sm p-7">
        <h2 class="text-xl font-bold text-gray-900 mb-6">SEO</h2>

        <div class="space-y-6">
            <div>
                <label class="text-sm font-semibold text-gray-800 mb-2 block">Meta Title</label>
                <input
                    type="text"
                    id="metaTitleInput"
                    name="settings[meta_title]"
                    value="{{ $editingBio->settings['meta_title'] ?? '' }}"
                    class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                <p class="text-xs text-gray-500 mt-1">This will appear as the page title when shared</p>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-800 mb-2 block">Meta Description</label>
                <textarea
                    id="metaDescriptionInput"
                    name="settings[meta_description]"
                    rows="3"
                    class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">{{ $editingBio->settings['meta_description'] ?? '' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">This will appear as the description when shared on social media</p>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-800 mb-2 block">Meta Image</label>
                <div class="mt-1 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition">
                    <input
                        type="file"
                        name="settings[meta_image]"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                </div>
                <p class="text-xs text-gray-500 mt-1">Upload an image for social media previews</p>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-800 mb-2 block">Custom Favicon</label>
                <div class="mt-1 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition">
                    <input
                        type="file"
                        name="settings[favicon]"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                </div>
                <p class="text-xs text-gray-500 mt-1">Upload a custom favicon for your bio page</p>
            </div>
        </div>
    </div>

    {{-- ================= SETTINGS SECTION ================= --}}
    <div class="bg-white rounded-xl border shadow-sm p-7">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Settings</h2>

        <div class="space-y-6">

            {{-- DISPLAY AVATAR --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Display Avatar</p>
                    <p class="text-xs text-gray-500 mt-1">Display or hide your avatar from your Bio page</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                    <input
                        type="checkbox"
                        id="showAvatarCheckbox"
                        name="settings[show_avatar]"
                        value="1"
                        class="sr-only peer"
                        {{ ($editingBio->settings['show_avatar'] ?? false) ? 'checked' : '' }}>
                    <div class="relative w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 transition-colors duration-300">
                        <div class="absolute top-[3px] left-[3px] bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm peer-checked:translate-x-6"></div>
                    </div>
                </label>
            </div>

            {{-- SENSITIVE CONTENT --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Sensitive Content</p>
                    <p class="text-xs text-gray-500 mt-1">Sensitive content warns users before showing them the Bio Page</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                    <input
                        type="checkbox"
                        id="sensitiveContentCheckbox"
                        name="settings[sensitive]"
                        value="1"
                        class="sr-only peer"
                        {{ ($editingBio->settings['sensitive'] ?? false) ? 'checked' : '' }}>
                    <div class="relative w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 transition-colors duration-300">
                        <div class="absolute top-[3px] left-[3px] bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm peer-checked:translate-x-6"></div>
                    </div>
                </label>
            </div>

            {{-- COOKIE POPUP --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Cookie Popup</p>
                    <p class="text-xs text-gray-500 mt-1">Cookie popup allows users to review cookie collection terms</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                    <input
                        type="checkbox"
                        name="settings[cookie_popup]"
                        value="1"
                        class="sr-only peer"
                        {{ ($editingBio->settings['cookie_popup'] ?? false) ? 'checked' : '' }}>
                    <div class="relative w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 transition-colors duration-300">
                        <div class="absolute top-[3px] left-[3px] bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm peer-checked:translate-x-6"></div>
                    </div>
                </label>
            </div>

            {{-- SHARE ICON --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Share Icon</p>
                    <p class="text-xs text-gray-500 mt-1">Share icon allows users to quickly share the Bio Page</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                    <input
                        type="checkbox"
                        name="settings[share_icon]"
                        value="1"
                        class="sr-only peer"
                        {{ ($editingBio->settings['share_icon'] ?? false) ? 'checked' : '' }}>
                    <div class="relative w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 transition-colors duration-300">
                        <div class="absolute top-[3px] left-[3px] bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm peer-checked:translate-x-6"></div>
                    </div>
                </label>
            </div>

            {{-- REMOVE BRANDING --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Remove Branding</p>
                    <p class="text-xs text-gray-500 mt-1">Remove our branding from your Bio Page</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                    <input
                        type="checkbox"
                        id="removeBrandingCheckbox"
                        name="settings[remove_branding]"
                        value="1"
                        class="sr-only peer"
                        {{ ($editingBio->settings['remove_branding'] ?? false) ? 'checked' : '' }}>
                    <div class="relative w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 transition-colors duration-300">
                        <div class="absolute top-[3px] left-[3px] bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm peer-checked:translate-x-6"></div>
                    </div>
                </label>
            </div>

            {{-- AVATAR STYLE --}}
            <div class="pt-4">
                <label class="text-sm font-semibold text-gray-800 mb-2 block">Avatar Style</label>
                <select
                    id="avatarStyleSelect"
                    name="settings[avatar_style]"
                    class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <option value="rounded" {{ ($editingBio->settings['avatar_style'] ?? 'rounded') === 'rounded' ? 'selected' : '' }}>Rounded</option>
                    <option value="circle" {{ ($editingBio->settings['avatar_style'] ?? 'rounded') === 'circle' ? 'selected' : '' }}>Circle</option>
                    <option value="square" {{ ($editingBio->settings['avatar_style'] ?? 'rounded') === 'square' ? 'selected' : '' }}>Square</option>
                </select>
            </div>

            {{-- PASSWORD --}}
            <div class="pt-4">
                <label class="text-sm font-semibold text-gray-800 mb-2 block">Password Protection</label>
                <input
                    type="password"
                    name="settings[password]"
                    placeholder="Type your password"
                    class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                <p class="text-xs text-gray-500 mt-2">By adding a password, you can restrict the access to your Bio Page</p>
            </div>

        </div>
    </div>

</div>

{{-- SUCCESS MESSAGE (Hidden by default) --}}
<div id="settingsSuccessMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
    ✓ Settings updated successfully!
</div>

<script>
// This script will communicate with the Content Tab's Live Preview
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to save settings to localStorage
    function saveSettingToLocalStorage(name, value) {
        localStorage.setItem(
    'bio_setting_' + name + '_' + window.CURRENT_BIO_ID,
    value
);

        console.log('💾 Saved to localStorage: bio_setting_' + name, '=', value);
    }
    
    // Function to update live preview in Content tab
    function updateLivePreview(setting, value) {
        // Dispatch a custom event that Content tab can listen to
        const event = new CustomEvent('settingsUpdated', {
            detail: { 
                setting: setting, 
                value: value
            }
        });
        window.dispatchEvent(event);
        
        console.log('📢 Setting sent to Live Preview:', setting, '=', value);
    }
    
    // Function to show sensitive content warning in Live Preview
    function showSensitiveWarningInPreview() {
        console.log('⚠️ Sensitive content warning triggered');
        
        // Trigger a special event for sensitive content
        const sensitiveEvent = new CustomEvent('showSensitiveWarning', {
            detail: { show: true }
        });
        window.dispatchEvent(sensitiveEvent);
    }
    
    // Function to update meta info in Live Preview and Browser
    function updateMetaInfoInPreview(title, description) {
        console.log('📝 Meta info update:', title || '', description || '');
        
        // Update Browser Title
        if (title) {
            document.title = title + ' | QRURL';
        }
        
        // Update Browser Meta Description
        if (description) {
            let metaDesc = document.querySelector('meta[name="description"]');
            if (!metaDesc) {
                metaDesc = document.createElement('meta');
                metaDesc.name = "description";
                document.head.appendChild(metaDesc);
            }
            metaDesc.content = description;
        }
        
        // Send event to Live Preview
        const metaEvent = new CustomEvent('updateMetaInfo', {
            detail: { 
                metaTitle: title || '',
                metaDescription: description || ''
            }
        });
        window.dispatchEvent(metaEvent);
    }
    
    // Function to update Live Preview branding
    function updateBrandingInPreview(remove) {
        const brandingEvent = new CustomEvent('updateBranding', {
            detail: { removeBranding: remove }
        });
        window.dispatchEvent(brandingEvent);
    }
    
    // Function to update avatar in Live Preview
    function updateAvatarInPreview(show, style) {
        const avatarEvent = new CustomEvent('updateAvatar', {
            detail: { 
                showAvatar: show,
                avatarStyle: style
            }
        });
        window.dispatchEvent(avatarEvent);
    }
    
    // Monitor all settings inputs for real-time preview
    const settingsInputs = document.querySelectorAll('#settingsTab input[name^="settings"], #settingsTab select[name^="settings"], #settingsTab textarea[name^="settings"]');
    
    settingsInputs.forEach(input => {
        input.addEventListener('change', function() {
            const name = this.name.replace('settings[', '').replace(']', '');
            let value = this.type === 'checkbox' ? this.checked : this.value;
            
            // Save to localStorage
            saveSettingToLocalStorage(name, value);
            
            // Send update to Live Preview
            updateLivePreview(name, value);
            
            // Special handling for sensitive content
            if (name === 'sensitive' && value === true) {
                showSensitiveWarningInPreview();
            }
            
            // Special handling for meta title and description
            if (name === 'meta_title') {
                updateMetaInfoInPreview(value, null);
            }
            if (name === 'meta_description') {
                updateMetaInfoInPreview(null, value);
            }
            
            // Special handling for branding
            if (name === 'remove_branding') {
                updateBrandingInPreview(value);
            }
            
            // Special handling for avatar
            if (name === 'show_avatar' || name === 'avatar_style') {
                const showAvatar = document.querySelector('#showAvatarCheckbox')?.checked || false;
                const avatarStyle = document.querySelector('#avatarStyleSelect')?.value || 'rounded';
                updateAvatarInPreview(showAvatar, avatarStyle);
            }
            
            // Show success message for any change
            showSettingsSuccessMessage();
        });
        
        // Also update on input for text fields (real-time typing)
        if (input.type === 'text' || input.tagName === 'TEXTAREA') {
            input.addEventListener('input', function() {
                const name = this.name.replace('settings[', '').replace(']', '');
                const value = this.value;
                
                // Save to localStorage
                saveSettingToLocalStorage(name, value);
                
                // Send update to Live Preview
                updateLivePreview(name, value);
                
                // Update meta info in real-time
                if (name === 'meta_title') {
                    updateMetaInfoInPreview(value, null);
                }
                if (name === 'meta_description') {
                    updateMetaInfoInPreview(null, value);
                }
            });
        }
    });
    
    // Initialize Live Preview with current settings
    setTimeout(() => {
        console.log('🚀 Initializing Live Preview with current settings...');
        
        // Send all current settings to Live Preview
        settingsInputs.forEach(input => {
            const name = input.name.replace('settings[', '').replace(']', '');
            let value;
            
            if (input.type === 'checkbox') {
                value = input.checked;
            } else if (input.tagName === 'SELECT' || input.type === 'text' || input.type === 'password' || input.tagName === 'TEXTAREA') {
                value = input.value;
            }
            
            if (value !== undefined) {
                // Save initial values to localStorage
                saveSettingToLocalStorage(name, value);
                
                updateLivePreview(name, value);
                
                // Special initialization for sensitive content
                if (name === 'sensitive' && value === true) {
                    showSensitiveWarningInPreview();
                }
                
                // Special initialization for meta info
                if (name === 'meta_title' && value) {
                    updateMetaInfoInPreview(value, null);
                }
                if (name === 'meta_description' && value) {
                    updateMetaInfoInPreview(null, value);
                }
                
                // Special initialization for branding
                if (name === 'remove_branding') {
                    updateBrandingInPreview(value);
                }
                
                // Special initialization for avatar
                if (name === 'show_avatar' || name === 'avatar_style') {
                    const showAvatar = document.querySelector('#showAvatarCheckbox')?.checked || false;
                    const avatarStyle = document.querySelector('#avatarStyleSelect')?.value || 'rounded';
                    updateAvatarInPreview(showAvatar, avatarStyle);
                }
            }
        });
    }, 1000);
    
});

// Function to show success message
function showSettingsSuccessMessage() {
    const successMsg = document.getElementById('settingsSuccessMessage');
    if (successMsg) {
        successMsg.classList.remove('hidden');
        
        // Hide message after 2 seconds
        setTimeout(() => {
            successMsg.classList.add('hidden');
        }, 2000);
    }
}

// Handle Enter key in form
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.matches('#settingsTab input[name^="settings"]')) {
        e.preventDefault();
        const input = e.target;
        const name = input.name.replace('settings[', '').replace(']', '');
        const value = input.value;
        
        // Save to localStorage
        localStorage.setItem('bio_setting_' + name, value);
        
        // Update Live Preview
        window.dispatchEvent(new CustomEvent('settingsUpdated', {
            detail: { 
                setting: name, 
                value: value
            }
        }));
        
        // Update meta info if it's meta title
        if (name === 'meta_title') {
            window.dispatchEvent(new CustomEvent('updateMetaInfo', {
                detail: { 
                    metaTitle: value,
                    metaDescription: ''
                }
            }));
        }
        
        // Show quick update message
        showSettingsSuccessMessage();
    }
});
</script>