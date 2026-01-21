<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $bio->title }}</title>
    <link rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#7c3aed">

    <!-- Tailwind CSS with responsive configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                screens: {
                    'xs': '375px',
                    'sm': '640px',
                    'md': '768px',
                    'lg': '1024px',
                    'xl': '1280px',
                }
            }
        }
    </script>
<style>

    body, a, button {
        transition: background-color 0.3s ease, 
                    color 0.3s ease, 
                    border-radius 0.3s ease,
                    box-shadow 0.3s ease;
    }
    .bg-purple-600, .bg-purple-100, 
    a[class*="bg-purple"], 
    a[class*="button"] {
        transition: all 0.3s ease !important;
    }

.social-links-container {
    margin: 1.5rem 0;
}

.social-icon {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
}

.social-icon.with-bg {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.social-icon.icon-only {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

@media (max-width: 640px) {
    .social-icon {
        width: 40px !important;
        height: 40px !important;
    }
    .social-icon.icon-only {
        width: 36px !important;
        height: 36px !important;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
   function loadPublicSettings() {

    const metaTitle = localStorage.getItem(
        'bio_setting_meta_title_{{ $bio->_id }}'
    );

    const metaDesc = localStorage.getItem(
        'bio_setting_meta_description_{{ $bio->_id }}'
    );

    const sensitive = localStorage.getItem(
        'bio_setting_sensitive_{{ $bio->_id }}'
    );

    const showAvatar = localStorage.getItem(
        'bio_setting_show_avatar_{{ $bio->_id }}'
    );

    const avatarStyle = localStorage.getItem(
        'bio_setting_avatar_style_{{ $bio->_id }}'
    );

    const removeBranding = localStorage.getItem(
        'bio_setting_remove_branding_{{ $bio->_id }}'
    );
       
        console.log('Loading public settings from localStorage...');
        
        if (metaTitle) {
            document.title = metaTitle;
            
            const pageTitle = document.querySelector('h1');
            if (pageTitle) pageTitle.textContent = metaTitle;
        }
        
        if (metaDesc) {
            let metaTag = document.querySelector('meta[name="description"]');
            if (!metaTag) {
                metaTag = document.createElement('meta');
                metaTag.name = 'description';
                document.head.appendChild(metaTag);
            }
            metaTag.content = metaDesc;
            const descEl = document.getElementById('pageDescription');
            if (descEl) descEl.textContent = metaDesc;
        }
        if (sensitive === 'true') {
            const warningHtml = `
                <div id="publicSensitiveWarning" style="
                    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(0,0,0,0.95); z-index: 9999;
                    display: flex; align-items: center; justify-content: center;
                    padding: 20px;">
                    <div style="background: white; padding: 30px; border-radius: 15px;
                               max-width: 400px; text-align: center;">
                        <div style="font-size: 48px; margin-bottom: 15px;">⚠️</div>
                        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">
                            Sensitive Content
                        </h2>
                        <p style="color: #666; margin-bottom: 20px; line-height: 1.5;">
                            This page contains sensitive content which may not be suitable for all ages. 
                            By continuing, you agree to our terms of service.
                        </p>
                        <button onclick="document.getElementById('publicSensitiveWarning').remove()" 
                                style="background: #7c3aed; color: white; padding: 12px 30px; 
                                       border: none; border-radius: 8px; font-weight: bold; 
                                       cursor: pointer; width: 100%;">
                            Continue
                        </button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('afterbegin', warningHtml);
        }
        
        const avatar = document.querySelector('.avatar-container img, .profile-avatar');
        if (avatar) {
            if (showAvatar === 'false') {
                avatar.style.display = 'none';
            }
            if (avatarStyle) {
                avatar.classList.remove('rounded-full', 'rounded-lg', 'rounded-none');
                if (avatarStyle === 'circle') {
                    avatar.classList.add('rounded-full');
                } else if (avatarStyle === 'square') {
                    avatar.classList.add('rounded-none');
                } else {
                    avatar.classList.add('rounded-lg');
                }
            }
        }
        if (removeBranding === 'true') {
            const brandingElements = document.querySelectorAll('.powered-by, .branding, footer');
            brandingElements.forEach(el => {
                if (el.textContent.includes('QRURL') || el.textContent.includes('Powered')) {
                    el.style.display = 'none';
                }
            });
        }
    }
    
    loadPublicSettings();
    const urlParams = new URLSearchParams(window.location.search);
    const sharedTitle = urlParams.get('title');
    const sharedDesc = urlParams.get('desc');
    
    if (sharedTitle) {
        document.title = sharedTitle;
    }
    if (sharedDesc) {
        let metaDesc = document.querySelector('meta[name="description"]');
        if (metaDesc) metaDesc.content = sharedDesc;
    }
    
});
</script>    
    <style>
        @media (max-width: 375px) {
            .container-extra-small {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
        .break-anywhere {
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        @media (max-width: 640px) {
            .touch-target {
                min-height: 44px;
                min-width: 44px;
            }
        }
        html {
            -webkit-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
@php
$design = $bio->design ?? [];

$backgroundType  = $design['background_type'] ?? 'color';
$backgroundValue = $design['background_value'] ?? '#f3f4f6'; // light gray
$textColor       = $design['text_color'] ?? '#111827';
$font            = $design['font'] ?? 'font-sans';
@endphp


<!-- ADD THIS AFTER <body> OPENING TAG -->
<body
    class="min-h-screen flex items-center justify-center p-4 sm:p-6"
    style="
        background: {{ $backgroundValue }};
        color: {{ $textColor }};
        font-family:
            {{ $font === 'font-serif' ? 'serif'
                : ($font === 'font-mono' ? 'monospace' : 'sans-serif') }};
    "
>
    @if(!empty($bio->settings['meta_title']) || !empty($bio->settings['meta_description']))
    <div class="absolute top-4 left-4 right-4 bg-white p-4 rounded-lg shadow-sm border max-w-md mx-auto">
        @if(!empty($bio->settings['meta_title']))
        <h1 class="text-lg font-bold text-gray-900 mb-1">{{ $bio->settings['meta_title'] }}</h1>
        @endif
        @if(!empty($bio->settings['meta_description']))
        <p class="text-sm text-gray-600">{{ $bio->settings['meta_description'] }}</p>
        @endif
    </div>
    @endif
    
    <div class="w-full max-w-md sm:max-w-lg md:max-w-xl bg-white rounded-2xl shadow-lg p-4 sm:p-6 md:p-8 text-center mx-auto mt-16">

        <div class="mb-4 sm:mb-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 mx-auto bg-purple-100 rounded-xl flex items-center justify-center">
                <span class="text-purple-600 font-bold text-xl sm:text-2xl md:text-3xl">
                    QR
                </span>
            </div>
        </div>
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 break-anywhere px-2">
            {{ $bio->title }}
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 mb-6 sm:mb-8 break-anywhere px-2">
            {{ url('/b/'.$bio->slug) }}
        </p>
        <div class="space-y-3 sm:space-y-4 md:space-y-5">
      @forelse($links as $link)

    @if(($link['type'] ?? '') === 'tagline' && ($link['enabled'] ?? true))
        <p class="text-sm text-gray-600 text-center break-anywhere">
            {{ $link['text'] }}
        </p>
    @endif

    @if(($link['type'] ?? '') === 'heading' && ($link['enabled'] ?? true))
        @php
            $tag = $link['style'] ?? 'h5';
        @endphp

        <{{ $tag}}
            class="font-semibold text-center break-anywhere"
            style="color: {{ $link['color'] ?? '#000' }}"
        >
            {{ $link['text'] }}
        </{{ $tag }}>
    @endif

    {{-- LINK --}}
    @if(($link['type'] ?? '') === 'link' && ($link['enabled'] ?? true))
        <a href="{{ $link['url'] }}"
           target="_blank"
           class="block w-full py-3 px-4 rounded-xl bg-purple-600 text-white text-center font-medium">
            {{ $link['text'] }}
        </a>
    @endif

    {{-- TEXT --}}
    @if(($link['type'] ?? '') === 'text' && ($link['enabled'] ?? true))
        <div class="text-gray-700 text-sm leading-relaxed break-anywhere">
            {!! $link['text'] !!}
        </div>
    @endif

    {{-- DIVIDER --}}
    @if(($link['type'] ?? '') === 'divider')
        <hr style="
            border-top: {{ $link['height'] ?? 1 }}px
            {{ $link['style'] ?? 'solid' }}
            {{ $link['color'] ?? '#000' }};
            margin: 16px 0;
        ">
    @endif

    {{-- HTML --}}
    @if(($link['type'] ?? '') === 'html')
        <div class="text-sm text-gray-700">
            {!! $link['text'] !!}
        </div>
    @endif

    {{-- IMAGE --}}
    @if(($link['type'] ?? '') === 'image')
        <div class="flex justify-center">
            <img src="{{asset('storage/' .$link['file']) }}"
                 class="max-w-full rounded-xl"
                 alt="">
        </div>
    @endif
{{-- PHONE CALL --}}
@if(($link['type'] ?? '') === 'phone_call' && ($link['enabled'] ?? true))
<a href="tel:{{ $link['phone'] }}"
   class="flex items-center justify-center gap-3 w-full py-3 px-4
          rounded-xl bg-purple-600 text-white font-medium
          hover:opacity-90 active:opacity-80">

    {{-- Phone Icon --}}
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M2.003 5.884l2-3.5A2 2 0 015.618 1h3.764a2 2 0 011.97 1.652l.5 3a2 2 0 01-.878 2.023l-1.7 1.133a11.042 11.042 0 005.516 5.516l1.133-1.7a2 2 0 012.023-.878l3 .5A2 2 0 0121 14.618v3.764a2 2 0 01-1.384 1.902l-3.5 1A2 2 0 0114.116 21C6.82 19.127.873 13.18-1 5.884z"/>
    </svg>

    <span>{{ $link['label'] ?? 'Call Now' }}</span>
</a>
@endif
{{-- WHATSAPP CALL --}}
@if(($link['type'] ?? '') === 'whatsapp_call' && ($link['enabled'] ?? true))
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $link['phone'] ?? '') }}"
   target="_blank"
   class="flex items-center justify-center gap-2 bg-green-500 text-white py-3 rounded-xl font-medium">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M16.7 13.5c-.3-.1-1.8-.9-2.1-1s-.5-.1-.7.1-.8 1-.9 1.2-.3.2-.6.1a7.5 7.5 0 01-3.5-3.1c-.3-.5.3-.5.8-1.7.1-.2 0-.4-.1-.6l-.9-2.1c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 3s1.2 3.6 1.4 3.9c.2.3 2.3 3.5 5.6 4.9z"/>
    </svg>
    {{ $link['label'] ?? 'Call on WhatsApp' }}
</a>
@endif

{{-- WHATSAPP MESSAGE --}}
@if(($link['type'] ?? '') === 'whatsapp_message' && ($link['enabled'] ?? true))
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $link['phone'] ?? '') }}?text={{ urlencode($link['message'] ?? '') }}"
   target="_blank"
   class="flex items-center justify-center gap-2 bg-green-100 text-green-700 py-3 rounded-xl font-medium">
    💬 {{ $link['label'] ?? 'Message on WhatsApp' }}
</a>
@endif
@if($link['type'] === 'video')
<video controls class="w-full rounded-xl">
    <source src="{{ asset('storage/'.$link['file']) }}">
</video>
@endif
@if($link['type'] === 'audio')
<audio controls class="w-full">
    <source src="{{ asset('storage/'.$link['file']) }}">
</audio>
@endif
{{-- PDF --}}
@if(($link['type'] ?? '') === 'pdf' && ($link['enabled'] ?? true))
<a href="{{ asset('storage/'.$link['file']) }}"
   target="_blank"
   class="flex items-center justify-between w-full
          py-3 px-4 rounded-xl
          bg-gray-100 text-gray-800
          font-medium hover:bg-gray-200">

    <span class="flex items-center gap-2">
        📄 {{ $link['title'] ?? 'View PDF' }}
    </span>

    <span class="text-xs text-gray-500">Open</span>
</a>
@endif
@if(
    ($link['type'] ?? '') === 'youtube' &&
    ($link['enabled'] ?? true) &&
    !empty($link['url'])
)
@php
    $url = trim($link['url']);

    preg_match(
        '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|shorts\/|embed\/))([^&\/\?]+)/',
        $url,
        $m
    );

    $videoId = $m[1] ?? null;
@endphp

@if($videoId)
<iframe
    class="w-full aspect-video rounded-xl"
    src="https://www.youtube.com/embed/{{ $videoId }}"
    frameborder="0"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
    allowfullscreen>
</iframe>
@endif
@endif

{{-- SPOTIFY --}}
@if(($link['type'] ?? '') === 'spotify' && !empty($link['url']) && ($link['enabled'] ?? true))
<div class="w-full rounded-xl overflow-hidden">
    <iframe
        src="https://open.spotify.com/embed/{{ \Illuminate\Support\Str::after($link['url'], 'open.spotify.com/') }}"
        width="100%"
        height="80"
        frameborder="0"
        allow="encrypted-media">
    </iframe>
</div>
@endif
@if(($link['type'] ?? '') === 'instagram' && ($link['enabled'] ?? true))
<a href="{{ $link['url'] }}"
   target="_blank"
   class="flex items-center justify-center gap-2
          bg-pink-100 text-pink-700
          py-3 rounded-xl font-medium text-sm">
    📸 View Instagram Post
</a>
@endif
@if(($link['type'] ?? '') === 'maps' && ($link['enabled'] ?? true))
<a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($link['address'] ?? '') }}"
   target="_blank"
   class="flex items-center justify-center gap-2
          bg-blue-100 text-blue-700
          py-3 rounded-xl font-medium text-sm">
    📍 {{ $link['address'] }}
</a>
@endif
{{-- FAQ BLOCK --}}
@if(($link['type'] ?? '') === 'faq' && ($link['enabled'] ?? true))
<div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-left">

    {{-- Question --}}
    <p class="font-medium text-sm sm:text-base text-gray-900">
        ❓ {{ $link['question'] }}
    </p>

    {{-- Answer --}}
    <p class="text-sm sm:text-base text-gray-600 mt-2 leading-relaxed">
        {{ $link['answer'] }}
    </p>

</div>
@endif
{{-- CONTACT FORM (PUBLIC – NO ROUTE) --}}
@if(($link['type'] ?? '') === 'contact_form' && ($link['enabled'] ?? true))
<div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-left space-y-3">

    <p class="font-medium text-sm sm:text-base text-gray-900">
        📩 {{ $link['text'] ?? 'Contact Me' }}
    </p>

    <form onsubmit="return submitContactForm(this)" class="space-y-3">

        <input
            type="email"
            name="email"
            required
            placeholder="Your email"
            class="w-full px-3 py-2 border rounded-lg text-sm">

        <textarea
            name="message"
            rows="3"
            required
            placeholder="Your message"
            class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>

        @if(!empty($link['disclaimer']))
        <label class="flex items-start gap-2 text-xs text-gray-600">
            <input type="checkbox" required>
            {{ $link['disclaimer'] }}
        </label>
        @endif

        <button
            type="submit"
            class="w-full bg-purple-600 text-white py-2 rounded-lg text-sm font-medium">
            Send
        </button>
    </form>

</div>
@endif

{{-- NEWSLETTER (PUBLIC – NO ROUTE) --}}
@if(($link['type'] ?? '') === 'newsletter' && ($link['enabled'] ?? true))
<div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center space-y-3">

    <p class="font-medium text-sm sm:text-base text-gray-900">
        📬 {{ $link['text'] ?? 'Subscribe' }}
    </p>

    @if(!empty($link['description']))
    <p class="text-xs sm:text-sm text-gray-600">
        {{ $link['description'] }}
    </p>
    @endif

    <form onsubmit="return submitNewsletter(this)" class="space-y-3">

        <input
            type="email"
            name="email"
            required
            placeholder="Your email"
            class="w-full px-3 py-2 border rounded-lg text-sm">

        @if(!empty($link['disclaimer']))
        <label class="flex items-start gap-2 text-xs text-gray-600 text-left">
            <input type="checkbox" required>
            {{ $link['disclaimer'] }}
        </label>
        @endif

        <button
            type="submit"
            class="w-full bg-purple-600 text-white py-2 rounded-lg text-sm font-medium">
            Subscribe
        </button>
    </form>

</div>
@endif


@empty
    <p class="text-gray-400 text-center">No content added yet</p>
@endforelse

        </div>
<!-- Social Links Section -->
@php
    $socialLinks = $bio->social_links['items'] ?? [];
    $socialPosition = $bio->social_links['position'] ?? 'top';
    $socialStyle = $bio->social_display_style ?? 'icon_bg';
    
    // Get social platforms config
    $socialsConfig = config('socials');
@endphp

@if(!empty($socialLinks) && count($socialLinks) > 0)
    <div class="social-links-container 
                @if($socialPosition === 'top') order-first mb-6 @endif
                @if($socialPosition === 'bottom') order-last mt-8 @endif">
        
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
            @foreach($socialLinks as $social)
                @php
                    $platform = $social['platform'] ?? '';
                    $url = $social['url'] ?? '#';
                    $platformData = $socialsConfig[$platform] ?? $socialsConfig['custom'] ?? [
                        'icon' => 'link',
                        'color' => '#6b7280',
                        'label' => $platform
                    ];
                @endphp
                
                <a href="{{ $url }}" 
                   target="_blank"
                   class="social-icon 
                          @if($socialStyle === 'icon_bg') with-bg @else icon-only @endif
                          inline-flex items-center justify-center 
                          hover:scale-110 transition-transform duration-200"
                   style="
                        @if($socialStyle === 'icon_bg')
                            background-color: {{ $platformData['color'] }}20;
                            border: 1px solid {{ $platformData['color'] }}40;
                            width: 44px;
                            height: 44px;
                        @else
                            background: transparent;
                            border: none;
                            width: 40px;
                            height: 40px;
                        @endif">
                    
                    @if($platformData['icon'] === 'link' || $platformData['icon'] === 'globe')
                        <svg width="{{ $socialStyle === 'icon_only' ? '22' : '20' }}" 
                             height="{{ $socialStyle === 'icon_only' ? '22' : '20' }}" 
                             viewBox="0 0 20 20" 
                             fill="{{ $platformData['color'] }}">
                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 100-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z"/>
                        </svg>
                    @else
                        <i class="fab fa-{{ $platformData['icon'] }}" 
                           style="color: {{ $platformData['color'] }};
                                  font-size: {{ $socialStyle === 'icon_only' ? '20px' : '18px' }}"></i>
                    @endif
                </a>
            @endforeach
        </div>
        
    </div>
@endif
        <!-- Footer -->
        <p class="mt-6 sm:mt-8 text-xs sm:text-sm text-gray-400">
            Powered by Tracklio
        </p>
    </div>

    <!-- Safe Area for notched phones -->
    <div class="safe-area-bottom"></div>

    <script>
        // iOS Safari 100vh fix
        function setVH() {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        setVH();
        window.addEventListener('resize', setVH);
        window.addEventListener('orientationchange', setVH);
        
        // Prevent zoom on input focus for iOS
        document.addEventListener('touchstart', function() {}, {passive: true});
        
        // Add CSS for safe areas
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                :root {
                    --safe-area-inset-top: env(safe-area-inset-top);
                    --safe-area-inset-bottom: env(safe-area-inset-bottom);
                    --safe-area-inset-left: env(safe-area-inset-left);
                    --safe-area-inset-right: env(safe-area-inset-right);
                }
                
                body {
                    padding-left: calc(1rem + var(--safe-area-inset-left, 0px));
                    padding-right: calc(1rem + var(--safe-area-inset-right, 0px));
                    padding-top: calc(1rem + var(--safe-area-inset-top, 0px));
                    padding-bottom: calc(1rem + var(--safe-area-inset-bottom, 0px));
                }
                
                .safe-area-bottom {
                    height: var(--safe-area-inset-bottom, 0px);
                    width: 100%;
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    z-index: -1;
                }
                
              
                
                /* Improve link tap highlight */
                a {
                    -webkit-tap-highlight-color: rgba(147, 51, 234, 0.2);
                    tap-highlight-color: rgba(147, 51, 234, 0.2);
                }
            </style>
        `);
    </script>
<script>
/* CONTACT FORM */
function submitContactForm(form) {
    const email = form.email.value.trim();
    const msg   = form.message.value.trim();

    if (!email || !msg) {
        alert('Please fill all fields');
        return false;
    }

    window.location.href =
        "mailto:your@email.com" +
        "?subject=Contact Request" +
        "&body=" + encodeURIComponent(msg + "\n\nFrom: " + email);

    return false; 
}

function submitNewsletter(form) {
    const email = form.email.value.trim();

    if (!email) {
        alert('Please enter email');
        return false;
    }

    window.location.href =
        "mailto:your@email.com" +
        "?subject=Newsletter Subscription" +
        "&body=Please subscribe this email:\n\n" + email;

    return false;
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    function applyDesign() {
        const design = JSON.parse(
            localStorage.getItem('bio_design_{{ $bio->_id }}') || '{}'
        );

        if (design.background_value) {
            document.body.style.background = design.background_value;
        }

        if (design.font) {
            document.body.classList.remove(
                'font-sans','font-serif','font-mono',
                'tracking-wide','italic','uppercase'
            );
            document.body.classList.add(design.font);
        }

        if (design.text_color) {
            document.body.style.color = design.text_color;
        }

        document.querySelectorAll('a.bg-purple-600, button').forEach(btn => {
            if (design.button_color) btn.style.backgroundColor = design.button_color;
            if (design.button_text_color) btn.style.color = design.button_text_color;
        });
    }

    applyDesign();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Loading design on public page');
    
    function applyDesign() {
        try {
const design = JSON.parse(
    localStorage.getItem('bio_design_{{ $bio->_id }}') || '{}'
);
            
            if (design.theme || design.background_value) {
                document.body.style.background = design.theme || design.background_value;
            }
            
            if (design.font) {
                document.body.classList.remove('font-sans','font-serif','font-mono','tracking-wide','italic','uppercase');
                document.body.classList.add(design.font);
            }
            
            if (design.text_color) {
                document.body.style.color = design.text_color;
            }
            
            const buttons = document.querySelectorAll('a.bg-purple-600, a[class*="bg-purple"], button, .btn');
            buttons.forEach(btn => {
                if (design.button_color) btn.style.backgroundColor = design.button_color;
                if (design.button_text_color) btn.style.color = design.button_text_color;
                if (design.button_radius) {
                    btn.classList.remove('rounded-lg','rounded-none','rounded-full');
                    btn.classList.add(design.button_radius);
                }
                if (design.button_shadow !== undefined) {
                    btn.classList.remove('shadow-md','shadow-xl');
                    if (design.button_shadow) btn.classList.add(design.button_shadow);
                }
            });
            
            console.log('Design applied to public page');
        } catch (error) {
            console.error('Error applying design:', error);
        }
    }
    applyDesign();
    window.addEventListener('storage', function(e) {
        if (e.key === 'bio_design') applyDesign();
    });
});
</script>
</body>
</html>