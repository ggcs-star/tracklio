<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#0D1321">
  <title>@yield('title') | tracklio</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/tracklio.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/tracklio.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <!-- Add this in your head section or @push('styles') -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-mart@5.5.2/css/emoji-mart.css">
<script src="https://cdn.jsdelivr.net/npm/emoji-mart@5.5.2/dist/browser.js"></script>

  @stack('styles')

  <style>
    
    .no-scroll { 
      overflow: hidden; 
      position: fixed;
      width: 100%;
      height: 100%;
    }

   
    [x-cloak] { 
      display: none !important; 
    }

    
    .safe-top {
      padding-top: env(safe-area-inset-top, 0);
    }
    .safe-bottom {
      padding-bottom: env(safe-area-inset-bottom, 0);
    }
    .safe-left {
      padding-left: env(safe-area-inset-left, 0);
    }
    .safe-right {
      padding-right: env(safe-area-inset-right, 0);
    }

 
    .hide-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }

    
    .touch-target {
      min-height: 44px;
      min-width: 44px;
    }

   
    * {
      transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
      transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
      transition-duration: 150ms;
    }

    
    :focus-visible {
      outline: 2px solid #4f46e5;
      outline-offset: 2px;
    }

    
    html {
      -webkit-text-size-adjust: 100%;
    }

   
    @media (prefers-reduced-motion: reduce) {
      * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }

    
    @media print {
      .no-print {
        display: none !important;
      }
      .print-only {
        display: block !important;
      }
    }
  </style>
  <script>
  window.isProUser = @json($isProUser ?? false);
</script>
</head>

<body class="bg-[#F5F7FB] text-gray-900 antialiased">

<div id="sidebarOverlay"
     class="hidden fixed inset-0 bg-black/50 z-40 backdrop-blur-sm transition-opacity duration-300"
     onclick="closeSidebar()"></div>

<div class="flex flex-col md:flex-row h-screen w-full overflow-hidden relative">
  
  
  <aside id="sidebar"
     class="fixed top-0 left-0 h-screen w-[280px] md:w-64 bg-[#0D1321] text-white shadow-xl z-50
            overflow-y-auto hide-scrollbar
            transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out
            safe-top safe-bottom safe-left">
    


    @include('layouts.sidebar')
  </aside>

  
  <main class="flex-1 flex flex-col md:ml-64 w-full overflow-hidden">
    
    
    <header class="h-[60px] sm:h-[72px] bg-white border-b flex items-center px-4 sm:px-6 md:px-8 lg:px-10 sticky top-0 z-30 safe-top">
    
      <button onclick="openSidebar()"
              class="md:hidden mr-4 p-2 rounded-lg hover:bg-gray-100 touch-target">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <div class="flex-1">
        @include('layouts.navbar')
      </div>
    </header>

   
    <div class="flex-1 overflow-y-auto hide-scrollbar p-4 sm:p-6 md:p-8 lg:p-10 safe-bottom">
      <div class="w-full max-w-[1920px] mx-auto">
        
       
        @if(session('success'))
          <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg sm:rounded-xl text-sm sm:text-base animate-fade-in">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ session('success') }}</span>
            </div>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg sm:rounded-xl text-sm sm:text-base animate-fade-in">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ session('error') }}</span>
            </div>
          </div>
        @endif

        @if(session('warning'))
          <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg sm:rounded-xl text-sm sm:text-base animate-fade-in">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ session('warning') }}</span>
            </div>
          </div>
        @endif

        @if(session('info'))
          <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg sm:rounded-xl text-sm sm:text-base animate-fade-in">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ session('info') }}</span>
            </div>
          </div>
        @endif

        
        @yield('content')

      </div>
    </div>

 
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-40 safe-bottom">
      <div class="flex items-center justify-around p-2">
        <button onclick="openSidebar()"
                class="flex flex-col items-center p-2 rounded-lg hover:bg-gray-50 touch-target">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <span class="text-xs mt-1 text-gray-600">Menu</span>
        </button>
        
        <a href="{{ url('/dashboard') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-gray-50 touch-target">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="text-xs mt-1 text-gray-600">Home</span>
        </a>
        
        <a href="{{ url('/qr-links') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-gray-50 touch-target">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
          </svg>
          <span class="text-xs mt-1 text-gray-600">QR</span>
        </a>
        
        <a href="{{ url('/profile') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-gray-50 touch-target">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-xs mt-1 text-gray-600">Profile</span>
        </a>
      </div>
    </nav>

  </main>
</div>


<button id="backToTop"
        class="fixed bottom-20 md:bottom-8 right-4 md:right-8 p-3 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-700 active:scale-95 transition-all duration-300 opacity-0 pointer-events-none touch-target z-40">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
  </svg>
</button>

@stack('scripts')
@yield('scripts')

<script>
  function openFacebookPopup() {
    const width = 600;
    const height = 700;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    
    window.open(
      '/facebook/connect',
      'Facebook Connect',
      `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
    );
  }
</script>

<script src="https://unpkg.com/lucide@latest"></script>

<script>

  document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) {
      lucide.createIcons();
    }
    
   
    initAnimations();
  
    initBackToTop();
   
    
  });

  
  function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const html = document.documentElement;
    
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
    setTimeout(() => overlay.classList.add('opacity-100'), 10);
    html.classList.add('no-scroll');
    
    
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const html = document.documentElement;
    
    sidebar.classList.add('-translate-x-full');
    overlay.classList.remove('opacity-100');
    setTimeout(() => overlay.classList.add('hidden'), 300);
    html.classList.remove('no-scroll');

    document.body.style.overflow = '';
  }

  
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSidebar();
  });

 
  document.addEventListener('click', e => {
    if (window.innerWidth < 768) {
      if (e.target.closest('a') && !e.target.closest('#sidebar')) {
        closeSidebar();
      }
    }
  });


  function initBackToTop() {
    const backToTopButton = document.getElementById('backToTop');
    const mainContent = document.querySelector('main > div');
    
    if (!backToTopButton || !mainContent) return;
    
    const toggleBackToTop = () => {
      if (mainContent.scrollTop > 300) {
        backToTopButton.classList.remove('opacity-0', 'pointer-events-none');
        backToTopButton.classList.add('opacity-100', 'pointer-events-auto');
      } else {
        backToTopButton.classList.remove('opacity-100', 'pointer-events-auto');
        backToTopButton.classList.add('opacity-0', 'pointer-events-none');
      }
    };
    
    mainContent.addEventListener('scroll', toggleBackToTop);
    
    backToTopButton.addEventListener('click', () => {
      mainContent.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }


  
  function initAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');
    animatedElements.forEach(el => {
      el.classList.add('animate-fade-in');
    });
  }

 
  window.addEventListener('orientationchange', () => {
   
    setTimeout(() => {
      window.dispatchEvent(new Event('resize'));
    }, 300);
  });

  const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
  if (isTouchDevice) {
    document.documentElement.classList.add('touch-device');
  } else {
    document.documentElement.classList.add('no-touch-device');
  }

  if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/service-worker.js').catch(err => {
        console.log('ServiceWorker registration failed: ', err);
      });
    });
  }

  
  window.addEventListener('online', () => {
    showToast('Back online', 'success');
  });

  window.addEventListener('offline', () => {
    showToast('You are offline', 'error');
  });

 
  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 md:top-6 md:right-6 z-50 px-4 py-3 rounded-xl shadow-2xl text-white font-medium transform transition-all duration-300 translate-x-full ${
      type === 'success' ? 'bg-green-500' :
      type === 'error' ? 'bg-red-500' :
      type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
    } max-w-[90vw] sm:max-w-md`;
    
    toast.innerHTML = `
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
          ${type === 'success' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' :
          type === 'error' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>' :
          type === 'warning' ? '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>' :
          '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'}
        </svg>
        <span class="text-sm">${message}</span>
      </div>
    `;
    
    document.body.appendChild(toast);
    
    
    requestAnimationFrame(() => {
      toast.style.transform = 'translateX(0)';
    });
    
   
    setTimeout(() => {
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

 
  document.addEventListener('keydown', (e) => {
   
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      const searchInput = document.querySelector('input[type="search"], input[placeholder*="search"], input[placeholder*="Search"]');
      if (searchInput) {
        searchInput.focus();
      }
    }
    
    
    if ((e.ctrlKey || e.metaKey) && e.key === '/') {
      e.preventDefault();
    }
  });

 
  document.addEventListener('invalid', (e) => {
   
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
      e.target.classList.add('border-red-500');
    }
  }, true);

  document.addEventListener('input', (e) => {
    
    if (e.target.classList.contains('border-red-500')) {
      e.target.classList.remove('border-red-500');
    }
  });

  
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          if (img.dataset.srcset) img.srcset = img.dataset.srcset;
          imageObserver.unobserve(img);
        }
      });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
      imageObserver.observe(img);
    });
  }

 
  const dpr = window.devicePixelRatio || 1;
  document.documentElement.setAttribute('data-dpr', dpr);

 
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (prefersReducedMotion.matches) {
    document.documentElement.classList.add('reduce-motion');
  }

  
  const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)');
  if (prefersDarkMode.matches) {
    document.documentElement.classList.add('dark-mode-preference');
  }
</script>

<style>
 
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
  }

  @keyframes slideInLeft {
    from {
      transform: translateX(-100%);
    }
    to {
      transform: translateX(0);
    }
  }

  @keyframes slideInRight {
    from {
      transform: translateX(100%);
    }
    to {
      transform: translateX(0);
    }
  }

  
  @media (max-width: 640px) {
    html {
      font-size: 14px;
    }
  }

  @media (min-width: 641px) and (max-width: 1024px) {
    html {
      font-size: 15px;
    }
  }

  @media (min-width: 1025px) {
    html {
      font-size: 16px;
    }
  }

 
  .overflow-y-auto {
    -webkit-overflow-scrolling: touch;
  }


  .break-words {
    overflow-wrap: break-word;
    word-wrap: break-word;
  }

 
  .table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  
  @media print {
    body {
      background: white !important;
      color: black !important;
    }
    
    .no-print {
      display: none !important;
    }
    
    a {
      color: black !important;
      text-decoration: underline !important;
    }
  }

 
  @media (prefers-contrast: high) {
    .high-contrast {
      border: 2px solid currentColor;
    }
  }

  
  @media (hover: none) {
    .hover-effect {
      opacity: 1 !important;
    }
  }
</style>

</body>
</html>