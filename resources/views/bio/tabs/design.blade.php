{{-- resources/views/bio/tabs/design.blade.php --}}
<div class="space-y-8" id="design-tab-container" 
     style="position: relative;">

    <!-- Content -->
    <div style="position: relative; z-index: 2;">
        {{-- ================= HEADER LAYOUT ================= --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-semibold mb-4">Header Layout</h3>
            <div class="grid grid-cols-3 gap-4">
                @foreach(['center','top','side'] as $layout)
                    <label class="cursor-pointer">
                        <input type="radio"
                               name="design[header_layout]"
                               value="{{ $layout }}"
                               class="sr-only design-input"
                               {{ ($editingBio->design['header_layout'] ?? 'center') === $layout ? 'checked' : '' }}>
                        <div class="border rounded-lg p-4 text-center transition hover:border-purple-300">
                            {{ ucfirst($layout) }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- ================= THEMES ================= --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-semibold mb-4">Themes</h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach([
                    '#ffffff',
                    'linear-gradient(135deg,#7c3aed,#ec4899)',
                    'linear-gradient(135deg,#06b6d4,#3b82f6)',
                    '#111827',
                    '#fde68a',
                    '#22c55e',
                    '#0f172a',
                    '#000000'
                ] as $theme)
                    <label class="cursor-pointer">
                        <input type="radio"
                               name="design[theme]"
                               value="{{ $theme }}"
                               class="sr-only design-input"
                               {{ ($editingBio->design['theme'] ?? '#ffffff') === $theme ? 'checked' : '' }}>
                        <div class="h-24 rounded-lg border-2 flex items-center justify-center font-medium text-white theme-preview"
                             style="background: {{ $theme }}"
                             data-theme="{{ $theme }}">
                            Hello
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- ================= FONTS ================= --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-semibold mb-4">Fonts</h3>
            <div class="grid grid-cols-6 gap-3">
                @foreach(['font-sans','font-serif','font-mono','tracking-wide','italic','uppercase'] as $font)
                    <label class="cursor-pointer">
                        <input type="radio"
                               name="design[font]"
                               value="{{ $font }}"
                               class="sr-only design-input"
                               {{ ($editingBio->design['font'] ?? 'font-sans') === $font ? 'checked' : '' }}>
                        <div class="border rounded-lg p-3 text-center {{ $font }} hover:border-purple-300 font-preview">
                            ABC
                        </div>
                    </label>
                @endforeach
            </div>
            <div class="mt-4 flex items-center gap-3">
                <label class="text-sm font-medium">Text Color</label>
                <input type="color"
                       name="design[text_color]"
                       value="{{ $editingBio->design['text_color'] ?? '#111827' }}"
                       class="design-input h-9 w-14 border rounded cursor-pointer">
            </div>
        </div>

        {{-- ================= BACKGROUND ================= --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-semibold mb-4">Custom Background</h3>
            <div class="flex gap-3 mb-4">
                @foreach(['color','gradient','image'] as $bg)
                    <label class="cursor-pointer">
                        <input type="radio"
                               name="design[background_type]"
                               value="{{ $bg }}"
                               class="sr-only design-input"
                               {{ ($editingBio->design['background_type'] ?? 'color') === $bg ? 'checked' : '' }}>
                        <span class="px-4 py-2 border rounded-lg hover:border-purple-300">{{ ucfirst($bg) }}</span>
                    </label>
                @endforeach
            </div>
            <input type="color"
                   name="design[background_value]"
                   value="{{ $editingBio->design['background_value'] ?? '#ffffff' }}"
                   class="design-input h-10 w-24 border rounded mb-4 cursor-pointer">
            <input type="file"
                   name="design_background_image"
                   accept="image/*"
                   class="design-input block w-full text-sm">
        </div>

        {{-- ================= BUTTONS ================= --}}
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-semibold mb-4">Buttons</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <label class="text-sm w-32">Button Color</label>
                    <input type="color"
                           name="design[button_color]"
                           value="{{ $editingBio->design['button_color'] ?? '#7c3aed' }}"
                           class="design-input h-9 w-14 border rounded cursor-pointer">
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-sm w-32">Button Text</label>
                    <input type="color"
                           name="design[button_text_color]"
                           value="{{ $editingBio->design['button_text_color'] ?? '#ffffff' }}"
                           class="design-input h-9 w-14 border rounded cursor-pointer">
                </div>
                <div>
                    <label class="text-sm block mb-1">Button Radius</label>
                    <select name="design[button_radius]" class="design-input border rounded-lg px-3 py-2 w-full cursor-pointer">
                        <option value="rounded-lg" {{ ($editingBio->design['button_radius'] ?? 'rounded-lg') === 'rounded-lg' ? 'selected' : '' }}>Rounded</option>
                        <option value="rounded-none" {{ ($editingBio->design['button_radius'] ?? 'rounded-lg') === 'rounded-none' ? 'selected' : '' }}>Square</option>
                        <option value="rounded-full" {{ ($editingBio->design['button_radius'] ?? 'rounded-lg') === 'rounded-full' ? 'selected' : '' }}>Pill</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm block mb-1">Button Shadow</label>
                    <select name="design[button_shadow]" class="design-input border rounded-lg px-3 py-2 w-full cursor-pointer">
                        <option value="" {{ ($editingBio->design['button_shadow'] ?? '') === '' ? 'selected' : '' }}>None</option>
                        <option value="shadow-md" {{ ($editingBio->design['button_shadow'] ?? '') === 'shadow-md' ? 'selected' : '' }}>Soft</option>
                        <option value="shadow-xl" {{ ($editingBio->design['button_shadow'] ?? '') === 'shadow-xl' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ✅ PERFECTED DESIGN SCRIPT WITH LIVE PREVIEW
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Design tab initialized');
    
   const CONFIG = {
    STORAGE_KEY: 'bio_design_' + window.CURRENT_BIO_ID,
        PREVIEW_SELECTORS: [
            '#livePreview',
            '.live-preview',
            '[data-preview]',
            '.phone-preview',
            '.w-full.h-full.bg-white',
            '.rounded-\\[32px\\]',
            'iframe',
            '.preview-container'
        ],
        DEBOUNCE_DELAY: 100
    };
    
    // ================== 2. UTILITY FUNCTIONS ==================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // ================== 3. STORAGE MANAGEMENT ==================
    function saveDesign(key, value) {
        try {
            let design = JSON.parse(localStorage.getItem(CONFIG.STORAGE_KEY) || '{}');
            design[key] = value;
            localStorage.setItem(CONFIG.STORAGE_KEY, JSON.stringify(design));
            console.log('💾 Saved design:', key, '=', value);
            return design;
        } catch (error) {
            console.error('❌ Storage error:', error);
            return null;
        }
    }
    
    function loadDesign() {
        try {
            const saved = localStorage.getItem(CONFIG.STORAGE_KEY);
            return saved ? JSON.parse(saved) : {};
        } catch (error) {
            return {};
        }
    }
    
    // ================== 4. FIND PREVIEW ELEMENT ==================
    let cachedPreview = null;
    
    function findPreviewElement() {
        if (cachedPreview && document.body.contains(cachedPreview)) {
            return cachedPreview;
        }
        
        // Try all selectors
        for (let selector of CONFIG.PREVIEW_SELECTORS) {
            try {
                const element = document.querySelector(selector);
                if (element) {
                    console.log('✅ Found preview with selector:', selector);
                    cachedPreview = element;
                    return element;
                }
            } catch (e) {
                // Invalid selector, continue
            }
        }
        
        // Try iframes
        const iframes = document.querySelectorAll('iframe');
        for (let iframe of iframes) {
            try {
                if (iframe.src && iframe.src.includes('preview') || 
                    iframe.src && iframe.src.includes('bio/')) {
                    cachedPreview = iframe;
                    return iframe;
                }
            } catch (e) {
                // Cross-origin, skip
            }
        }
        
        console.warn('⚠️ No preview element found');
        return null;
    }
    
    // ================== 5. PREVIEW UPDATES ==================
    function updatePreview(key, value) {
        const preview = findPreviewElement();
        if (!preview) {
            console.log('📱 No local preview, design saved to storage');
            return;
        }
        
        console.log('🎨 Updating preview:', key, '=', value);
        
        // Handle iframe preview
        if (preview.tagName === 'IFRAME') {
            updateIframePreview(preview, key, value);
            return;
        }
        
        // Handle direct DOM preview
        updateDomPreview(preview, key, value);
    }
    
    function updateDomPreview(previewElement, key, value) {
        switch(key) {
            case 'theme':
            case 'background_value':
                previewElement.style.background = value;
                break;
                
            case 'font':
                // Remove all font classes
                ['font-sans','font-serif','font-mono','tracking-wide','italic','uppercase']
                    .forEach(cls => previewElement.classList.remove(cls));
                previewElement.classList.add(value);
                break;
                
            case 'text_color':
                previewElement.style.color = value;
                break;
                
            case 'button_color':
                updateButtons(previewElement, 'background-color', value);
                break;
                
            case 'button_text_color':
                updateButtons(previewElement, 'color', value);
                break;
                
            case 'button_radius':
                updateButtonStyles(previewElement, 'border-radius', value);
                break;
                
            case 'button_shadow':
                updateButtonStyles(previewElement, 'box-shadow', value);
                break;
        }
    }
    
    function updateButtons(container, property, value) {
        // Common button selectors
        const selectors = [
            'a', 'button',
            '[class*="bg-"]',
            '[class*="button"]',
            '.bg-purple-600',
            '.bg-purple-100',
            '.bg-green-500',
            '.bg-green-100',
            '.inline-flex',
            '.flex.items-center'
        ];
        
        selectors.forEach(selector => {
            const elements = container.querySelectorAll(selector);
            elements.forEach(el => {
                if (property === 'background-color') {
                    if (el.classList.contains('bg-purple-600') || 
                        el.classList.contains('bg-purple-100') ||
                        el.getAttribute('href')) {
                        el.style.backgroundColor = value;
                    }
                } else if (property === 'color') {
                    el.style.color = value;
                }
            });
        });
    }
    
    function updateButtonStyles(container, property, value) {
        const buttons = container.querySelectorAll('a, button');
        buttons.forEach(btn => {
            if (property === 'border-radius') {
                btn.classList.remove('rounded-lg', 'rounded-none', 'rounded-full');
                btn.classList.add(value);
            } else if (property === 'box-shadow') {
                btn.classList.remove('shadow-md', 'shadow-xl');
                if (value) btn.classList.add(value);
            }
        });
    }
    
    function updateIframePreview(iframe, key, value) {
        try {
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            if (!iframeDoc) return;
            
            // Post message for cross-origin iframes
            if (iframe.src && iframe.src !== window.location.origin) {
                iframe.contentWindow.postMessage({
                    type: 'design_update',
                    key: key,
                    value: value
                }, '*');
                return;
            }
            
            // Same-origin iframe
            switch(key) {
                case 'theme':
                case 'background_value':
                    iframeDoc.body.style.background = value;
                    break;
                    
                case 'font':
                    const fontClasses = ['font-sans','font-serif','font-mono','tracking-wide','italic','uppercase'];
                    fontClasses.forEach(cls => iframeDoc.body.classList.remove(cls));
                    iframeDoc.body.classList.add(value);
                    break;
                    
                case 'text_color':
                    iframeDoc.body.style.color = value;
                    break;
                    
                case 'button_color':
                    iframeDoc.querySelectorAll('a, button').forEach(el => {
                        if (el.classList.contains('bg-purple-600') || el.getAttribute('href')) {
                            el.style.backgroundColor = value;
                        }
                    });
                    break;
                    
                case 'button_text_color':
                    iframeDoc.querySelectorAll('a, button').forEach(el => {
                        if (el.classList.contains('bg-purple-600') || el.getAttribute('href')) {
                            el.style.color = value;
                        }
                    });
                    break;
            }
        } catch (error) {
            console.error('❌ Iframe update error:', error);
        }
    }
    
    // ================== 6. DESIGN UPDATE HANDLER ==================
  const updateDesign = debounce(function(key, value) {
    // 1️⃣ save in localStorage
    const design = saveDesign(key, value);

    // 2️⃣ sync with hidden input (FOR BACKEND SAVE)
    const hidden = document.getElementById('designJsonInput');
    if (hidden && design) {
        hidden.value = JSON.stringify(design);
    }

    // 3️⃣ live preview
    updatePreview(key, value);

    // 4️⃣ notify others
    window.dispatchEvent(new CustomEvent('designUpdated', {
        detail: { key, value }
    }));
}, CONFIG.DEBOUNCE_DELAY);

    
    // ================== 7. EVENT LISTENERS ==================
    function setupEventListeners() {
        const designContainer = document.getElementById('design-tab-container');
        if (!designContainer) return;
        
        // Handle all design inputs
        designContainer.querySelectorAll('.design-input').forEach(input => {
            // Change event for radios and selects
            input.addEventListener('change', function(e) {
                e.stopPropagation();
                
                let key, value;
                
                if (this.type === 'radio') {
                    const name = this.name;
                    const checked = document.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) return;
                    key = name.replace('design[', '').replace(']', '');
                    value = checked.value;
                } else {
                    key = this.name.replace('design[', '').replace(']', '');
                    value = this.value;
                }
                
                updateDesign(key, value);
            });
            
            // Input event for color inputs (live updates)
            if (input.type === 'color') {
                input.addEventListener('input', function(e) {
                    e.stopPropagation();
                    
                    const key = this.name.replace('design[', '').replace(']', '');
                    const value = this.value;
                    updateDesign(key, value);
                });
            }
        });
        
        // Visual feedback for checked items
        designContainer.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove checked style from siblings
                const parent = this.closest('label');
                if (parent) {
                    const siblings = parent.parentElement.querySelectorAll('label');
                    siblings.forEach(sib => {
                        const previewDiv = sib.querySelector('.theme-preview, .font-preview, span, div');
                        if (previewDiv) {
                            previewDiv.classList.remove('border-purple-500', 'ring-2', 'ring-purple-200');
                        }
                    });
                    
                    // Add checked style
                    const previewDiv = parent.querySelector('.theme-preview, .font-preview, span, div');
                    if (previewDiv) {
                        previewDiv.classList.add('border-purple-500', 'ring-2', 'ring-purple-200');
                    }
                }
            });
        });
    }
    
    // ================== 8. INITIALIZATION ==================
    function initializeDesign() {
        console.log('🔄 Initializing design...');
        
        // Load saved design
        const savedDesign = loadDesign();
        
        // Apply saved design to form
        document.querySelectorAll('.design-input').forEach(input => {
            if (input.type === 'radio') {
                const key = input.name.replace('design[', '').replace(']', '');
                if (savedDesign[key] === input.value) {
                    input.checked = true;
                    // Trigger visual update
                    input.dispatchEvent(new Event('change'));
                }
            } else if (input.type === 'color' || input.tagName === 'SELECT') {
                const key = input.name.replace('design[', '').replace(']', '');
                if (savedDesign[key] !== undefined) {
                    input.value = savedDesign[key];
                }
            }
        });
        
        // Update preview with current values
        const currentDesign = {};
        document.querySelectorAll('.design-input').forEach(input => {
            if (input.type === 'radio' && input.checked) {
                const key = input.name.replace('design[', '').replace(']', '');
                currentDesign[key] = input.value;
            } else if (input.type !== 'radio') {
                const key = input.name.replace('design[', '').replace(']', '');
                currentDesign[key] = input.value;
            }
        });
        
        // Apply all designs to preview
        Object.entries(currentDesign).forEach(([key, value]) => {
            updatePreview(key, value);
        });
        
        console.log('✅ Design initialized with', Object.keys(currentDesign).length, 'settings');
    }
    
    // ================== 9. DEBUG TOOLS ==================
    window.debugDesign = {
        show: function() {
            console.log('=== DESIGN DEBUG ===');
            console.log('Saved design:', loadDesign());
            console.log('Preview element:', findPreviewElement());
            console.log('All inputs:', document.querySelectorAll('.design-input').length);
        },
        reset: function() {
            localStorage.removeItem(CONFIG.STORAGE_KEY);
            console.log('🧹 Design storage cleared');
            location.reload();
        }
    };
    
    // ================== 10. SETUP ==================
    setupEventListeners();
    
    // Initialize after a short delay to ensure DOM is ready
    setTimeout(initializeDesign, 300);
    
    // Re-initialize if preview becomes available later
    const observer = new MutationObserver(() => {
        if (!cachedPreview) {
            const preview = findPreviewElement();
            if (preview) {
                console.log('🔍 Preview appeared, reapplying design');
                initializeDesign();
            }
        }
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
    
    console.log('🎉 Design system ready!');
});

// Minimal click protection (optional)
document.addEventListener('click', function(e) {
    if (e.target.closest('#design-tab-container .design-input, #design-tab-container label')) {
        e.stopPropagation();
    }
}, true);
</script>
<script>
function syncDesignBeforeSave() {
    const design = localStorage.getItem('bio_design');
    const input = document.getElementById('design_json');

    if (input && design) {
        input.value = design;
        console.log('✅ design synced before save');
    }
}
</script>
<style>
    /* Design preview styles */
    #design-tab-container .design-input {
        transition: all 0.2s ease;
    }
    
    #design-tab-container input[type="radio"]:checked + div,
    #design-tab-container input[type="radio"]:checked + span {
        border-color: #7c3aed !important;
        background-color: rgba(124, 58, 237, 0.05);
        box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.1);
    }
    
    #design-tab-container .theme-preview:hover,
    #design-tab-container .font-preview:hover {
        border-color: #a78bfa;
        transform: scale(1.02);
    }
    
    /* Smooth preview transitions */
    .live-preview,
    .w-full.h-full.bg-white,
    .rounded-\[32px\] {
        transition: background 0.3s ease, 
                   color 0.3s ease,
                   border-radius 0.3s ease;
    }
    
    .live-preview a,
    .live-preview button,
    .bg-purple-600,
    .bg-purple-100 {
        transition: background-color 0.3s ease, 
                   color 0.3s ease,
                   border-radius 0.3s ease,
                   box-shadow 0.3s ease !important;
    }
    
    /* Color input styling */
    input[type="color"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        padding: 0;
        cursor: pointer;
    }
    
    input[type="color"]::-webkit-color-swatch-wrapper {
        padding: 0;
    }
    
    input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 4px;
    }
    
    /* Focus states */
    .design-input:focus {
        outline: none;
        ring: 2px;
        ring-color: #8b5cf6;
        ring-offset: 2px;
    }
</style>