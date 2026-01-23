<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracklio - All-in-One Social Media Management Platform</title>
    
    <!-- Improved Font Setup -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        display: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in-down': 'fadeInDown 0.8s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                }
            }
        }
    </script>
    
    <style>
        /* Enhanced Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Glass Morphism Effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 4px;
        }
        
        /* Improved Container */
        .container-custom {
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .container-custom {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .container-custom {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        
        /* Animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md border-b border-slate-200/80 z-50 transition-all duration-300">
        <div class="container-custom">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- LOGO + BRAND -->
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('assets/images/tracklio.png') }}"
                        alt="Tracklio Logo"
                        class="h-8 w-8 md:h-10 md:w-10 object-contain"
                        loading="lazy"
                    >
                    <span class="text-xl md:text-2xl font-black text-indigo-600 tracking-tight">
                        Tracklio
                    </span>
                </div>

                <!-- MENU -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Features</a>
                    <a href="#why-tracklio" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Why Tracklio</a>
                    <a href="#how-it-works" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">How it works</a>
                    <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Pricing</a>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                        Sign in
                    </a>
                    <a href="{{ route('register.page') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-sm hover:shadow-lg transition-all">
                        Start Free Trial
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-slate-600 hover:text-slate-900 p-2 rounded-lg" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden py-4 border-t border-slate-100" id="mobile-menu">
                <div class="flex flex-col space-y-4">
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Features</a>
                    <a href="#why-tracklio" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Why Tracklio</a>
                    <a href="#how-it-works" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">How it works</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Pricing</a>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">
                        Sign in
                    </a>
                    <a href="{{ route('register.page') }}" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-medium text-sm text-center">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-28 md:pt-36 overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-white to-purple-50/50"></div>
        <div class="absolute top-10 left-10 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-float"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-float" style="animation-delay: 2s"></div>
        
        <div class="container-custom relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Main Heading -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-6 tracking-tight animate-fade-in-up">
                    Create. Manage. Track. Convert.
                </h1>
                
                <!-- Subheading -->
                <h2 class="text-xl md:text-2xl lg:text-3xl font-bold gradient-text mb-6 animate-fade-in-up" style="animation-delay: 0.1s">
                    Your All-in-One Platform for Social Media Management, QR Codes, Links, Pages & Analytics.
                </h2>
                
                <!-- Description -->
                <p class="text-lg text-slate-600 mb-10 leading-relaxed max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s">
                    A powerful all-in-one digital growth platform that lets you manage Instagram, Facebook, and YouTube from one dashboard, create dynamic QR codes, short URLs, bio pages and webpages, and track complete social media, sales, and marketing performance in real time.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 animate-fade-in-up" style="animation-delay: 0.3s">
                    <a href="{{ route('register.page') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-bold text-lg hover:shadow-xl transition-all">
                        Get Started Free
                    </a>
                    <button class="w-full sm:w-auto px-8 py-4 glass-card text-slate-900 rounded-full font-bold text-lg hover:shadow-xl transition-all" id="view-demo-btn">
                        Request a Demo
                    </button>
                </div>
                
                <!-- Tagline -->
                <p class="text-slate-500 text-sm animate-fade-in-up" style="animation-delay: 0.4s">
                    No multiple tools. No complexity. Just one powerful platform.
                </p>
            </div>

            <!-- Product Dashboard Mockup -->
          <!-- Product Dashboard Mockup -->
<div class="relative max-w-7xl mx-auto mt-20 animate-fade-in-up" style="animation-delay:0.4s">

    <!-- Badge with improved styling -->
    <div class="flex justify-center mb-10">
        <span class="inline-flex items-center gap-2
                     px-6 py-3 rounded-full
                     bg-gradient-to-r from-indigo-50 to-purple-50 
                     border border-indigo-100
                     text-indigo-700
                     text-sm md:text-base font-semibold
                     shadow-sm hover:shadow-md transition-shadow duration-300">
            <span class="w-2 h-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full"></span>
            🚀 The Solution: Tracklio replaces 5–6 tools with one unified growth platform
        </span>
    </div>

    <!-- IMAGE WITH SUBTLE ENHANCEMENTS -->
    <div class="relative flex justify-center">
        <!-- Subtle gradient shadow behind image -->
        <div class="absolute -inset-4 bg-gradient-to-r from-indigo-100 via-purple-100 to-pink-100 rounded-2xl blur-xl opacity-30 -z-10"></div>
        
        <!-- Image container with smooth hover effect -->
        <div class="relative group">
            <!-- Glow effect on hover -->
            <div class="absolute -inset-2 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 rounded-2xl blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 -z-10"></div>
            
            <img
                src="{{ asset('assets/images/tracklio1.png') }}"
                alt="Tracklio Dashboard Preview - All-in-One Social Media Management Platform"
                class="w-full max-w-5xl h-auto object-contain rounded-xl 
                       shadow-lg transition-all duration-500
                       group-hover:shadow-2xl group-hover:scale-[1.005]
                       border border-slate-100"
                loading="lazy"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'"
            />
            
            <!-- Subtle corner accents -->
            <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-indigo-300 rounded-tl-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-purple-300 rounded-tr-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-pink-300 rounded-bl-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-indigo-300 rounded-br-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
    </div>

    <!-- Optional caption/text below image -->
    <div class="text-center mt-8">
        <p class="text-slate-600 text-sm md:text-base max-w-2xl mx-auto">
            <span class="font-medium text-slate-700">One dashboard for everything:</span>
            Social media management, QR codes, link shortening, analytics, and more.
        </p>
    </div>
</div>
        </div>
    </section>

    <!-- Core Features Section -->
    <section id="features" class="py-20 md:py-28 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 md:mb-20 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">
                    WHAT MAKES TRACLIO ALL-IN-ONE
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Everything You Need to Manage, Measure & Grow — In One Platform</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature Card 1: Social Media Management -->
                <div class="group animate-on-scroll">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6">
                            <i class="fas fa-broadcast-tower text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Social Media Management</h3>
                        <p class="text-slate-600 leading-relaxed">Manage Instagram, Facebook, and YouTube from one dashboard</p>
                    </div>
                </div>

                <!-- Feature Card 2: Dynamic QR Codes -->
                <div class="group animate-on-scroll" style="animation-delay: 0.1s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6">
                            <i class="fas fa-qrcode text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Dynamic QR Codes</h3>
                        <p class="text-slate-600 leading-relaxed">Create editable, non-expiring QR codes anytime</p>
                    </div>
                </div>

                <!-- Feature Card 3: Smart URL Shortener -->
                <div class="group animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6">
                            <i class="fas fa-link text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Smart URL Shortener</h3>
                        <p class="text-slate-600 leading-relaxed">Generate short links with real-time tracking</p>
                    </div>
                </div>

                <!-- Feature Card 4: Bio Page & Webpage Builder -->
                <div class="group animate-on-scroll" style="animation-delay: 0.3s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6">
                            <i class="fas fa-file-code text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Bio Page & Webpage Builder</h3>
                        <p class="text-slate-600 leading-relaxed">Create your bio page or webpage in minutes</p>
                    </div>
                </div>

                <!-- Feature Card 5: Analytics & Stats Dashboard -->
                <div class="group animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6">
                            <i class="fas fa-chart-bar text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Analytics & Stats Dashboard</h3>
                        <p class="text-slate-600 leading-relaxed">Track views, clicks, impressions & locations</p>
                    </div>
                </div>

                <!-- Feature Card 6: Sales & Marketing Intelligence -->
                <div class="group animate-on-scroll" style="animation-delay: 0.5s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-xl transition-all duration-300 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6">
                            <i class="fas fa-bullseye text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Sales & Marketing Intelligence</h3>
                        <p class="text-slate-600 leading-relaxed">Analyze, measure sales & marketing performance and improve conversions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Management Deep Dive -->
    <section class="py-20 bg-slate-50">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Manage All Major Social Media Handles from One Dashboard</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Tracklio lets you manage your top social media platforms—Instagram, Facebook, and YouTube—without switching between apps or tools.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Centralized content and account management</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Time-saving workflow for creators and teams</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Consistent posting and monitoring</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Perfect for brands, agencies, and businesses</span>
                        </li>
                    </ul>
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold text-lg">One login. One dashboard. Complete control.</p>
                    </div>
                </div>
                <div class="animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-layer-group text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-center mb-4">Social Media Integration</h3>
                        <p class="text-slate-600 text-center">Everything You Need to Manage, Measure & Grow — In One Platform</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic QR Codes Deep Dive -->
    <section class="py-20 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Dynamic QR Codes That Never Expire</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Create smart QR codes that stay active forever and can be edited anytime without reprinting.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll order-2 lg:order-1">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-qrcode text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-center mb-4">Dynamic QR Codes</h3>
                        <p class="text-slate-600 text-center">Create editable, non-expiring QR codes anytime</p>
                    </div>
                </div>
                <div class="animate-on-scroll order-1 lg:order-2" style="animation-delay: 0.2s">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Non-expiring QR codes</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Change destination links anytime</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Track scan count, location & device data</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Ideal for marketing, packaging, events & visiting cards</span>
                        </li>
                    </ul>
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold text-lg">One QR code. Unlimited flexibility.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bio Page & Webpage Builder -->
    <section class="py-20 bg-slate-50">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Create Your Own Bio Page or Webpage in Minutes</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Build a professional bio page or mini-website without coding or design skills.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Add all your important links in one place</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Perfect for Instagram bio, WhatsApp & campaigns</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Mobile-optimized and fast loading</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Customizable and easy to manage</span>
                        </li>
                    </ul>
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold text-lg">Your digital identity, simplified.</p>
                    </div>
                </div>
                <div class="animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-code text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-center mb-4">Bio Page & Webpage Builder</h3>
                        <p class="text-slate-600 text-center">Create your own professional bio page or mini-website in minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- URL Shortener with Analytics -->
    <section class="py-20 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Short Links That Deliver Real Insights</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Tracklio's smart URL shortener helps you track every click and understand user behavior.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll order-2 lg:order-1">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-link text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-center mb-4">Smart URL Shortener</h3>
                        <p class="text-slate-600 text-center">Generate clean, branded short links with real-time tracking.</p>
                    </div>
                </div>
                <div class="animate-on-scroll order-1 lg:order-2" style="animation-delay: 0.2s">
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Create clean, branded short URLs</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Track clicks in real time</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">View visitor location and activity</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-1"></i>
                            <span class="text-slate-700">Ideal for ads, reels, stories & campaigns</span>
                        </li>
                    </ul>
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold text-lg">Short links that do more than redirect.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Analytics & Stats Dashboard -->
    <section class="py-20 bg-slate-50">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">All Your Performance Data — One Clear Dashboard</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Track and analyze everything you publish across platforms and links from one place.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">Metrics You Can Track:</h3>
                    <ul class="grid grid-cols-2 gap-4 mb-8">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-eye text-indigo-600"></i>
                            <span>Views & impressions</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-mouse-pointer text-indigo-600"></i>
                            <span>Clicks & engagement</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i>
                            <span>Visitor locations</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-chart-line text-indigo-600"></i>
                            <span>Content performance</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-bullhorn text-indigo-600"></i>
                            <span>Campaign effectiveness</span>
                        </li>
                    </ul>
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold text-lg">Turn data into decisions.</p>
                    </div>
                </div>
                <div class="animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-bar text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-center mb-4">Analytics & Stats Dashboard</h3>
                        <p class="text-slate-600 text-center">Track views, clicks, impressions, locations, and engagement in one place.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sales & Marketing Intelligence -->
    <section class="py-20 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Built for Sales & Marketing Performance</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Tracklio gives businesses and marketers the insights needed to optimize campaigns and improve ROI.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll order-2 lg:order-1">
                    <div class="bg-white p-8 rounded-2xl shadow-lg">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bullseye text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-center mb-4">Sales & Marketing Intelligence</h3>
                        <p class="text-slate-600 text-center">Measure campaign performance and optimize conversions using real data.</p>
                    </div>
                </div>
                <div class="animate-on-scroll order-1 lg:order-2" style="animation-delay: 0.2s">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">Use Cases:</h3>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-play-circle text-indigo-600 mt-1"></i>
                            <span class="text-slate-700">Measure reel and post performance</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-tags text-indigo-600 mt-1"></i>
                            <span class="text-slate-700">Track lead sources and traffic</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-money-bill-wave text-indigo-600 mt-1"></i>
                            <span class="text-slate-700">Optimize marketing spend</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-chart-pie text-indigo-600 mt-1"></i>
                            <span class="text-slate-700">Improve conversion rates</span>
                        </li>
                    </ul>
                    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold text-lg">Marketing without data is guessing. Tracklio removes guesswork.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Tracklio Section -->
    <section id="why-tracklio" class="py-20 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="container-custom">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black mb-4 tracking-tight">Why Tracklio?</h2>
                <p class="text-xl max-w-2xl mx-auto">Why Use Multiple Tools When One Does It All?</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <p class="text-lg text-indigo-100 mb-8">
                        Tracklio replaces multiple platforms with a single, easy-to-use system designed for creators, businesses, and marketers who want clarity and control.
                    </p>
                    
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-2xl">
                        <h3 class="text-xl font-bold mb-6">WHO IT'S FOR</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="font-medium">Creators & Influencers</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <span class="font-medium">Businesses & Startups</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span class="font-medium">Marketers & Agencies</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span class="font-medium">Sales Teams & Brands</span>
                            </div>
                        </div>
                        <p class="mt-6 font-semibold text-center">
                            If you grow online, Tracklio is built for you.
                        </p>
                    </div>
                </div>
                
                <div class="animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="bg-white/10 backdrop-blur-sm p-8 rounded-2xl">
                        <h3 class="text-2xl font-bold mb-6 text-center">HOW IT WORKS</h3>
                        <div class="space-y-8">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-white text-indigo-600 flex items-center justify-center text-xl font-bold shrink-0">
                                    1
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold mb-2">Connect your social accounts</h4>
                                    <p class="text-indigo-100">Link Instagram, Facebook, YouTube to get started</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-white text-indigo-600 flex items-center justify-center text-xl font-bold shrink-0">
                                    2
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold mb-2">Create QR codes, links & pages</h4>
                                    <p class="text-indigo-100">Generate dynamic QR codes, short URLs, and bio pages</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-white text-indigo-600 flex items-center justify-center text-xl font-bold shrink-0">
                                    3
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold mb-2">Track results and improve conversions</h4>
                                    <p class="text-indigo-100">Monitor performance and optimize with real data</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 md:py-28 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight animate-on-scroll">Simple, Transparent Pricing</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto animate-on-scroll" style="animation-delay: 0.1s">Perfect plan for growing your social media presence</p>
                
                <!-- Pricing Toggle -->
                <div class="flex items-center justify-center gap-4 mb-12 animate-on-scroll" style="animation-delay: 0.2s">
                    <span class="text-sm font-semibold text-slate-900">Monthly</span>
                    <button class="w-14 h-7 bg-indigo-100 rounded-full relative transition-colors duration-300" id="pricing-toggle">
                        <div class="absolute top-1 w-5 h-5 bg-indigo-600 rounded-full transition-all duration-300 left-1" id="toggle-circle"></div>
                    </button>
                    <span class="text-sm font-semibold text-slate-500">
                        Yearly 
                        <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs px-2 py-1 rounded-full ml-2">Save 20%</span>
                    </span>
                </div>
            </div>

            <div class="flex justify-center">
                <!-- Single Pro Plan -->
                <div class="relative animate-on-scroll max-w-md w-full">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-1.5 rounded-full text-xs font-bold tracking-wide">RECOMMENDED</div>
                    <div class="p-8 rounded-3xl border-2 border-indigo-500 shadow-2xl shadow-indigo-100 transition-all hover:scale-105 duration-300 h-full bg-white">
                        <h3 class="text-xl font-bold mb-2">Pro Plan</h3>
                        
                        <!-- Monthly Price -->
                        <div class="monthly-price">
                            <div class="flex items-baseline justify-center gap-1 mb-4">
                                <span class="text-4xl font-black">₹999</span>
                                <span class="text-slate-500">/month</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-8 text-center">Everything you need to grow your social media presence</p>
                        </div>
                        
                        <!-- Yearly Price (Hidden by default) -->
                        <div class="yearly-price hidden">
                            <div class="flex flex-col items-center mb-4">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-black">₹9,590</span>
                                    <span class="text-slate-500">/year</span>
                                </div>
                                <div class="text-sm text-slate-500 mt-1">
                                    <span class="line-through text-slate-400 mr-2">₹11,988</span>
                                    <span class="text-green-600 font-semibold">Save ₹2,398</span>
                                </div>
                                <p class="text-sm text-slate-600 mb-8 text-center mt-2">Only ₹799/month (billed annually)</p>
                            </div>
                        </div>
                        
                        <ul class="text-left space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-slate-700">
                                <i class="fas fa-check-circle text-indigo-500 text-base"></i>
                                Unlimited Projects & Links
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-700">
                                <i class="fas fa-check-circle text-indigo-500 text-base"></i>
                                10 Social Accounts
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-700">
                                <i class="fas fa-check-circle text-indigo-500 text-base"></i>
                                Advanced Analytics
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-700">
                                <i class="fas fa-check-circle text-indigo-500 text-base"></i>
                                Priority Support
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-700">
                                <i class="fas fa-check-circle text-indigo-500 text-base"></i>
                                Team Collaboration
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-700">
                                <i class="fas fa-check-circle text-indigo-500 text-base"></i>
                                Custom URL Shortener
                            </li>
                        </ul>
                        
                        <!-- Monthly Payment Summary -->
                        <div class="monthly-payment bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900">Monthly Total:</span>
                                <span class="text-lg font-bold text-indigo-600">₹999</span>
                            </div>
                        </div>
                        
                        <!-- Yearly Payment Summary (Hidden by default) -->
                        <div class="yearly-payment bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100 hidden">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Yearly Plan:</span>
                                <span class="text-sm font-semibold">₹9,590</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Original Price:</span>
                                <span class="text-sm font-semibold line-through text-slate-400">₹11,988</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">You Save:</span>
                                <span class="text-sm font-semibold text-green-600">₹2,398</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-900">Yearly Total:</span>
                                    <span class="text-lg font-bold text-indigo-600">₹9,590</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 text-right">(₹799/month)</p>
                            </div>
                        </div>
                        
                        <button class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-indigo-200 transition-all">
                            Get Started Now
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Yearly prices note -->
            <div class="mt-12 text-center text-sm text-slate-600 animate-on-scroll" style="animation-delay: 0.6s">
                <p id="yearly-note"><span class="font-semibold">Save ₹2,398</span> with yearly billing</p>
                <p class="text-xs text-slate-500 mt-2">All prices are in Indian Rupees (₹)</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-20 relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"></div>
        
        <div class="container-custom relative text-center text-white">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black mb-6 tracking-tight animate-on-scroll">One Platform. Total Control. Real Growth.</h2>
                <div class="animate-on-scroll" style="animation-delay: 0.2s">
                    <a href="{{ route('register.page') }}" class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-full font-black text-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 shadow-xl">
                        Start Free Today
                    </a>
                    <p class="text-sm text-indigo-200 mt-4">No credit card required • Cancel anytime</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-10">
        <div class="container-custom">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <div>
                    <!-- Tracklio Logo -->
                    <div class="flex items-center gap-3 mb-6">
                        <img
                            src="/assets/images/tracklio.png"
                            alt="Tracklio Logo"
                            class="h-10 w-10 object-contain"
                        >
                        <span class="text-2xl font-black text-white">
                            Tracklio
                        </span>
                    </div>
                    <p class="text-slate-400 leading-relaxed mb-6 text-sm">
                        5th Floor, Grand Emporio, Shiv Habitat B-Block, Motera Stadium Rd, opp. S Mall, Motera, Ahmedabad, Gujarat 380005
                    </p>
                </div>
                
                <!-- Product Column -->
                <div>
                    <h4 class="font-bold text-white mb-6">Product</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition-colors">How it works</a></li>
                    </ul>
                </div>

                <!-- Company Column -->
                <div>
                    <h4 class="font-bold text-white mb-6">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal Column -->
                <div>
                    <h4 class="font-bold text-white mb-6">Legal</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 text-center">
                <p class="text-sm text-slate-500">
                    © 2024 Tracklio Technologies Inc. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        // Pricing Toggle
        const pricingToggle = document.getElementById('pricing-toggle');
        const toggleCircle = document.getElementById('toggle-circle');
        const monthlyPriceDiv = document.querySelector('.monthly-price');
        const yearlyPriceDiv = document.querySelector('.yearly-price');
        const monthlyPaymentDiv = document.querySelector('.monthly-payment');
        const yearlyPaymentDiv = document.querySelector('.yearly-payment');
        const yearlyNote = document.getElementById('yearly-note');
        
        let isYearly = false;
        
        if (pricingToggle) {
            pricingToggle.addEventListener('click', function() {
                isYearly = !isYearly;
                
                if (isYearly) {
                    toggleCircle.style.left = 'calc(100% - 1.25rem)';
                    pricingToggle.classList.add('bg-indigo-200');
                    pricingToggle.classList.remove('bg-indigo-100');
                    monthlyPriceDiv.classList.add('hidden');
                    yearlyPriceDiv.classList.remove('hidden');
                    monthlyPaymentDiv.classList.add('hidden');
                    yearlyPaymentDiv.classList.remove('hidden');
                    yearlyNote.innerHTML = '<span class="font-semibold">Save ₹2,398</span> with yearly billing';
                } else {
                    toggleCircle.style.left = '0.25rem';
                    pricingToggle.classList.remove('bg-indigo-200');
                    pricingToggle.classList.add('bg-indigo-100');
                    monthlyPriceDiv.classList.remove('hidden');
                    yearlyPriceDiv.classList.add('hidden');
                    monthlyPaymentDiv.classList.remove('hidden');
                    yearlyPaymentDiv.classList.add('hidden');
                    yearlyNote.innerHTML = '<span class="font-semibold">Save ₹2,398</span> with yearly billing';
                }
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                e.preventDefault();
                
                const targetElement = document.querySelector(href);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (mobileMenu) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
        
        // View Demo button
        const viewDemoBtn = document.getElementById('view-demo-btn');
        if (viewDemoBtn) {
            viewDemoBtn.addEventListener('click', function() {
                alert('Demo request feature would be implemented here. In production, this would open a contact form or schedule a meeting.');
            });
        }
        
        // Scroll animations
        function checkScroll() {
            const elements = document.querySelectorAll('.animate-on-scroll');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('visible');
                }
            });
        }
        
        // Initial check
        checkScroll();
        
        // Check on scroll
        window.addEventListener('scroll', checkScroll);
        
        // Navbar scroll effect
        const navbar = document.querySelector('nav');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>