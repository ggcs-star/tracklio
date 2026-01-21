<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracklio - Design, Publish, Track</title>
    
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
                    fontSize: {
                        'xxs': '0.65rem',
                        '2xl': '1.5rem',
                        '3xl': '1.875rem',
                        '4xl': '2.25rem',
                        '5xl': '3rem',
                        '6xl': '3.75rem',
                        '7xl': '4.5rem',
                        '8xl': '6rem',
                    },
                    lineHeight: {
                        'tight': '1.1',
                        'relaxed': '1.75',
                    },
                    letterSpacing: {
                        'tight': '-0.025em',
                        'wide': '0.025em',
                        'wider': '0.05em',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in-down': 'fadeInDown 0.8s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'ping-slow': 'ping 3s cubic-bezier(0, 0, 0.2, 1) infinite',
                        'slide-in': 'slideIn 0.6s ease-out',
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
                        slideIn: {
                            '0%': { transform: 'translateX(-100px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                    typography: (theme) => ({
                        DEFAULT: {
                            css: {
                                color: theme('colors.slate.700'),
                                a: {
                                    color: theme('colors.indigo.600'),
                                    '&:hover': {
                                        color: theme('colors.indigo.800'),
                                    },
                                },
                                h1: {
                                    fontWeight: '800',
                                    letterSpacing: theme('letterSpacing.tight'),
                                },
                                h2: {
                                    fontWeight: '700',
                                    letterSpacing: theme('letterSpacing.tight'),
                                },
                                h3: {
                                    fontWeight: '600',
                                    letterSpacing: theme('letterSpacing.tight'),
                                },
                                p: {
                                    lineHeight: theme('lineHeight.relaxed'),
                                },
                            },
                        },
                    }),
                }
            }
        }
    </script>
    
    <style>
        /* Base Typography Improvements */
        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 400;
            line-height: 1.6;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Enhanced Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 25%, #ec4899 50%, #f59e0b 75%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% auto;
            animation: gradient-flow 8s ease infinite;
        }
        
        @keyframes gradient-flow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Glass Morphism Effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
        }
        
        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
        }
        
        /* Selection Styling */
        ::selection {
            background-color: rgba(79, 70, 229, 0.2);
            color: #4f46e5;
        }
        
        /* Focus States */
        *:focus {
            outline: 2px solid rgba(79, 70, 229, 0.5);
            outline-offset: 2px;
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
        
        /* Responsive Typography */
        .responsive-heading {
            font-size: clamp(2rem, 5vw, 4rem);
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        
        .responsive-subheading {
            font-size: clamp(1.125rem, 3vw, 1.5rem);
            line-height: 1.5;
            font-weight: 400;
        }
        
        /* Utility Classes */
        .text-balance {
            text-wrap: balance;
        }
        
        .hyphens-auto {
            hyphens: auto;
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
        
        /* Loading States */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 12pt;
                line-height: 1.4;
            }
            
            h1, h2, h3, h4 {
                page-break-after: avoid;
            }
            
            img {
                max-width: 100% !important;
                page-break-inside: avoid;
            }
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
        class="h-8 w-8 md:h-10 md:w-10 object-contain transition-transform duration-300 hover:scale-110"
        loading="lazy"
    >
                    <span class="text-xl md:text-2xl font-black text-indigo-600 tracking-tight">
                        Tracklio
                    </span>
                </div>

                <!-- MENU -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors duration-300">Features</a>
                    <a href="#how-it-works" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors duration-300">How it works</a>
                    <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors duration-300">Pricing</a>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors duration-300">
                        Sign in
                    </a>
                    <a href="{{ route('register.page') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-semibold text-sm hover:shadow-lg hover:shadow-indigo-200 transition-all duration-300 hover:-translate-y-0.5">
                        Start Free Trial
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100 transition-colors" id="mobile-menu-button">
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
                    <a href="#how-it-works" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">How it works</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">Pricing</a>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors py-2">
                        Sign in
                    </a>
                    <a href="{{ route('register.page') }}" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-medium text-sm text-center hover:shadow-lg hover:shadow-indigo-200 transition-all">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-28 md:pt-36 lg:pt-40 overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-white to-purple-50/50"></div>
        <div class="absolute top-10 left-10 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-float"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-float" style="animation-delay: 2s"></div>
        
        <div class="container-custom relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 text-xs font-bold tracking-wide uppercase mb-8 animate-fade-in-down">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping-slow absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                    New: Unified Social Dashboard 2.0
                </div>
                
                <!-- Main Heading -->
                <h1 class="responsive-heading text-slate-900 mb-6 tracking-tight text-balance animate-fade-in-up">
                    Design, Publish, Track.<br />
                    <span class="gradient-text">All in One Place.</span>
                </h1>
                
                <!-- Subheading -->
                <p class="responsive-subheading text-slate-600 mb-10 leading-relaxed text-balance max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.1s">
                    The ultimate design and social media analytics engine. Manage your creative assets, schedule posts, and track performance with pixel-perfect accuracy.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16 animate-fade-in-up" style="animation-delay: 0.2s">
                    <a href="{{ route('register.page') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-bold text-base hover:shadow-2xl hover:shadow-indigo-300 hover:-translate-y-1 transition-all duration-300">
                        Start Free Trial
                    </a>
                    <button class="w-full sm:w-auto px-8 py-4 glass-card text-slate-900 rounded-full font-bold text-base hover:shadow-xl hover:-translate-y-1 transition-all duration-300" id="view-demo-btn">
                        View Demo
                    </button>
                </div>

                <!-- Product Dashboard Mockup -->
             <!-- Product Dashboard Mockup -->
<div class="relative max-w-5xl mx-auto group animate-fade-in-up" style="animation-delay: 0.3s">
    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition duration-1000 group-hover:duration-200"></div>
    
    <div class="relative rounded-2xl border border-slate-200/80 shadow-2xl overflow-hidden bg-white">
        <!-- Dashboard Image with Perfect Settings -->
        <div class="relative w-full aspect-[16/9] md:aspect-[21/9]">
            <img 
                src="{{ asset('assets/images/dashboard.png') }}"
                alt="Tracklio Dashboard Preview"
                class="w-full h-full object-contain md:object-cover"
                loading="lazy"
            />
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/5 via-transparent to-transparent"></div>
            
            <!-- Hover Effect Container -->
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/10 via-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <!-- Interactive Elements Overlay -->
        <div class="absolute bottom-4 right-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-medium text-slate-700 rounded-full border border-slate-200">
                Interactive Preview
            </span>
            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
        </div>
    </div>
    
    <!-- Floating Elements (Optional) -->
    <div class="absolute -top-3 -right-3 w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full blur-xl opacity-20 group-hover:opacity-30 transition-opacity duration-500"></div>
    <div class="absolute -bottom-3 -left-3 w-16 h-16 bg-gradient-to-tr from-pink-500 to-purple-500 rounded-full blur-xl opacity-15 group-hover:opacity-25 transition-opacity duration-500"></div>
</div>
    </section>

    <!-- Core Features Section -->
    <section id="features" class="py-20 md:py-28 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 md:mb-20 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">Powerful Features for Modern Teams</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto text-balance">Everything you need to scale your digital presence from a single, unified interface.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature Card 1 -->
                <div class="group animate-on-scroll">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-2xl transition-all duration-500 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-layer-group text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Design Status Tracking</h3>
                        <p class="text-slate-600 leading-relaxed">Keep your team in sync with clear statuses: Draft, In Review, Approved, or Published. Never lose track of your progress.</p>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div class="group animate-on-scroll" style="animation-delay: 0.1s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-2xl transition-all duration-500 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-share-alt text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Social Media Integration</h3>
                        <p class="text-slate-600 leading-relaxed">Connect Instagram, Facebook, LinkedIn, and Twitter directly. One-click publishing to all your channels.</p>
                    </div>
                </div>

                <!-- Feature Card 3 -->
                <div class="group animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-2xl transition-all duration-500 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-link text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Smart URL Shortener</h3>
                        <p class="text-slate-600 leading-relaxed">Create brandable short links and track detailed visitor insights including geolocation, device, and referrers.</p>
                    </div>
                </div>

                <!-- Feature Card 4 -->
                <div class="group animate-on-scroll" style="animation-delay: 0.3s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-2xl transition-all duration-500 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-line text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Real-time Analytics</h3>
                        <p class="text-slate-600 leading-relaxed">Monitor engagement metrics like likes, reach, and clicks in real-time with beautiful, data-rich charts.</p>
                    </div>
                </div>

                <!-- Feature Card 5 -->
                <div class="group animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-2xl transition-all duration-500 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tachometer-alt text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Unified Dashboard</h3>
                        <p class="text-slate-600 leading-relaxed">Access your entire creative ecosystem from one place. No more switching between 10 different browser tabs.</p>
                    </div>
                </div>

                <!-- Feature Card 6 -->
                <div class="group animate-on-scroll" style="animation-delay: 0.5s">
                    <div class="p-8 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-2xl transition-all duration-500 h-full">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Scalable for Teams</h3>
                        <p class="text-slate-600 leading-relaxed">Built for collaboration. Invite team members, assign roles, and streamline your approval process.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 md:py-28 bg-gradient-to-b from-slate-50 to-white">
        <div class="container-custom">
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight animate-on-scroll">How It Works</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto text-balance animate-on-scroll" style="animation-delay: 0.1s">Simple three-step process to transform your workflow</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 relative">
                <div class="hidden md:block absolute top-12 left-0 right-0 h-1 bg-gradient-to-r from-indigo-200 via-purple-200 to-pink-200 -z-10 animate-on-scroll"></div>
                
                <!-- Step 1 -->
                <div class="flex flex-col items-center text-center animate-on-scroll">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-white to-indigo-50 border-4 border-white shadow-2xl flex items-center justify-center text-2xl font-black text-indigo-600 mb-6 relative z-10 group hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative">01</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Design</h3>
                    <p class="text-slate-600">Upload or create your designs using our integrated tools and track their status in real-time.</p>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center text-center animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-white to-purple-50 border-4 border-white shadow-2xl flex items-center justify-center text-2xl font-black text-purple-600 mb-6 relative z-10 group hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative">02</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Publish</h3>
                    <p class="text-slate-600">Schedule and push your content across all social media channels with just one single click.</p>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center text-center animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-white to-pink-50 border-4 border-white shadow-2xl flex items-center justify-center text-2xl font-black text-pink-600 mb-6 relative z-10 group hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-100 to-indigo-100 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="relative">03</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Track</h3>
                    <p class="text-slate-600">Gain deep insights into post performance and URL analytics to optimize your next campaign.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 md:py-28 bg-white">
        <div class="container-custom">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="animate-on-scroll">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-6 tracking-tight">Built for the Modern Workflow</h2>
                    <div class="space-y-6">
                        <!-- Benefit 1 -->
                        <div class="flex items-start gap-4 animate-on-scroll" style="animation-delay: 0.1s">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center shrink-0 mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Save Time</h4>
                                <p class="text-slate-600 text-sm md:text-base">Automate repetitive tasks and manage all accounts from one window.</p>
                            </div>
                        </div>

                        <!-- Benefit 2 -->
                        <div class="flex items-start gap-4 animate-on-scroll" style="animation-delay: 0.2s">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center shrink-0 mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Centralized Control</h4>
                                <p class="text-slate-600 text-sm md:text-base">Keep your branding consistent and your assets organized.</p>
                            </div>
                        </div>

                        <!-- Benefit 3 -->
                        <div class="flex items-start gap-4 animate-on-scroll" style="animation-delay: 0.3s">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center shrink-0 mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Data-driven Decisions</h4>
                                <p class="text-slate-600 text-sm md:text-base">Stop guessing. Use real metrics to inform your marketing strategy.</p>
                            </div>
                        </div>

                        <!-- Benefit 4 -->
                        <div class="flex items-start gap-4 animate-on-scroll" style="animation-delay: 0.4s">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center shrink-0 mt-1">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Enterprise Security</h4>
                                <p class="text-slate-600 text-sm md:text-base">Bank-grade encryption and secure integrations with OAuth.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="animate-on-scroll" style="animation-delay: 0.5s">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition duration-700"></div>
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                            <img 
                                src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=1200" 
                                alt="Team collaborating" 
                                class="w-full h-auto transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Use Cases Section -->
    <section class="py-20 md:py-28 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="container-custom">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black mb-12 md:mb-16 text-center tracking-tight animate-on-scroll">Perfect for Every Business</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <!-- Use Case 1 -->
                <div class="p-8 rounded-2xl glass-dark hover:scale-105 transition-all duration-300 animate-on-scroll">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Marketing Agencies</h3>
                    <p class="text-indigo-100">Manage dozens of clients from one dashboard. Generate reports automatically.</p>
                </div>

                <!-- Use Case 2 -->
                <div class="p-8 rounded-2xl glass-dark hover:scale-105 transition-all duration-300 animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6">
                        <i class="fas fa-user-edit text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Solo Creators</h3>
                    <p class="text-indigo-100">Focus on creating while we handle the distribution and analytics.</p>
                </div>

                <!-- Use Case 3 -->
                <div class="p-8 rounded-2xl glass-dark hover:scale-105 transition-all duration-300 animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Marketing Teams</h3>
                    <p class="text-indigo-100">Collaborate seamlessly on design approvals and social scheduling.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Integrations Section -->
    <section class="py-20 md:py-28 bg-white">
        <div class="container-custom">
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight animate-on-scroll">Seamlessly Integrated</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto text-balance animate-on-scroll" style="animation-delay: 0.1s">Connect with all your favorite tools and platforms</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 md:gap-8">
                <!-- Instagram -->
                <div class="flex flex-col items-center gap-4 group animate-on-scroll">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-50 to-rose-50 flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-sm hover:shadow-xl">
                        <i class="fab fa-instagram text-3xl bg-gradient-to-r from-purple-600 via-pink-600 to-yellow-500 bg-clip-text text-transparent"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Instagram</span>
                </div>

                <!-- Facebook -->
                <div class="flex flex-col items-center gap-4 group animate-on-scroll" style="animation-delay: 0.1s">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-sm hover:shadow-xl">
                        <i class="fab fa-facebook text-3xl text-blue-600"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Facebook</span>
                </div>

                <!-- LinkedIn -->
                <div class="flex flex-col items-center gap-4 group animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-50 to-cyan-50 flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-sm hover:shadow-xl">
                        <i class="fab fa-linkedin text-3xl text-blue-700"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">LinkedIn</span>
                </div>

                <!-- Twitter -->
                <div class="flex flex-col items-center gap-4 group animate-on-scroll" style="animation-delay: 0.3s">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-sky-50 to-blue-50 flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-sm hover:shadow-xl">
                        <i class="fab fa-twitter text-3xl text-sky-500"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Twitter</span>
                </div>

                <!-- Slack -->
                <div class="flex flex-col items-center gap-4 group animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-sm hover:shadow-xl">
                        <i class="fab fa-slack text-3xl text-purple-600"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Slack</span>
                </div>

                <!-- Google Ads -->
                <div class="flex flex-col items-center gap-4 group animate-on-scroll" style="animation-delay: 0.5s">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-red-50 to-yellow-50 flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-sm hover:shadow-xl">
                        <i class="fab fa-google text-3xl text-red-600"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Google Ads</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 md:py-28 bg-gradient-to-b from-slate-50 to-white">
        <div class="container-custom">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-center mb-12 md:mb-16 tracking-tight animate-on-scroll">Trusted by 10,000+ Teams</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 animate-on-scroll">
                    <div class="mb-6">
                        <div class="flex text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-600 italic">"Tracklio has completely transformed our workflow. Managing designs and social posts in one place saves us at least 10 hours every week."</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://picsum.photos/id/64/100/100" class="w-12 h-12 rounded-full border-2 border-white shadow" alt="Sarah Jenkins" loading="lazy">
                        <div>
                            <h4 class="font-bold text-slate-900">Sarah Jenkins</h4>
                            <p class="text-sm text-slate-500">Marketing Director at FlowState</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="mb-6">
                        <div class="flex text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-600 italic">"The URL analytics are a game-changer. I finally know exactly where my traffic is coming from and which designs convert best."</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://picsum.photos/id/65/100/100" class="w-12 h-12 rounded-full border-2 border-white shadow" alt="Marcus Thorne" loading="lazy">
                        <div>
                            <h4 class="font-bold text-slate-900">Marcus Thorne</h4>
                            <p class="text-sm text-slate-500">Solo Content Creator</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="mb-6">
                        <div class="flex text-yellow-400 mb-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-600 italic">"The status tracking feature makes client approvals so much smoother. It's the most polished design platform I've used."</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://picsum.photos/id/66/100/100" class="w-12 h-12 rounded-full border-2 border-white shadow" alt="Elena Rodriguez" loading="lazy">
                        <div>
                            <h4 class="font-bold text-slate-900">Elena Rodriguez</h4>
                            <p class="text-sm text-slate-500">Agency Founder</p>
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
            <p class="text-lg text-slate-600 max-w-2xl mx-auto text-balance animate-on-scroll" style="animation-delay: 0.1s">Perfect plan for growing your social media presence</p>
            
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
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Plan Amount:</span>
                            <span class="text-sm font-semibold">₹999</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900">Monthly Total:</span>
                                <span class="text-lg font-bold text-indigo-600">₹999</span>
                            </div>
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
<script>
// Pricing Toggle Script
document.addEventListener('DOMContentLoaded', function() {
    const pricingToggle = document.getElementById('pricing-toggle');
    const toggleCircle = document.getElementById('toggle-circle');
    const monthlyPriceDiv = document.querySelector('.monthly-price');
    const yearlyPriceDiv = document.querySelector('.yearly-price');
    const monthlyPaymentDiv = document.querySelector('.monthly-payment');
    const yearlyPaymentDiv = document.querySelector('.yearly-payment');
    const monthlyText = document.querySelectorAll('#pricing .text-sm.font-semibold')[0];
    const yearlyText = document.querySelectorAll('#pricing .text-sm.font-semibold')[1];
    const yearlyNote = document.getElementById('yearly-note');
    
    let isYearly = false;
    
    pricingToggle.addEventListener('click', function() {
        isYearly = !isYearly;
        
        if (isYearly) {
            // Move toggle to yearly (right side)
            toggleCircle.style.left = 'calc(100% - 1.25rem)';
            pricingToggle.classList.remove('bg-indigo-100');
            pricingToggle.classList.add('bg-indigo-200');
            
            // Show yearly prices, hide monthly
            monthlyPriceDiv.classList.add('hidden');
            yearlyPriceDiv.classList.remove('hidden');
            monthlyPaymentDiv.classList.add('hidden');
            yearlyPaymentDiv.classList.remove('hidden');
            
            // Update text colors
            monthlyText.classList.remove('text-slate-900');
            monthlyText.classList.add('text-slate-500');
            yearlyText.classList.remove('text-slate-500');
            yearlyText.classList.add('text-slate-900');
            
            // Update yearly note
            yearlyNote.innerHTML = '<span class="font-semibold">Save ₹2,398</span> with yearly billing';
            
        } else {
            // Move toggle to monthly (left side)
            toggleCircle.style.left = '0.25rem';
            pricingToggle.classList.remove('bg-indigo-200');
            pricingToggle.classList.add('bg-indigo-100');
            
            // Show monthly prices, hide yearly
            monthlyPriceDiv.classList.remove('hidden');
            yearlyPriceDiv.classList.add('hidden');
            monthlyPaymentDiv.classList.remove('hidden');
            yearlyPaymentDiv.classList.add('hidden');
            
            // Update text colors
            monthlyText.classList.remove('text-slate-500');
            monthlyText.classList.add('text-slate-900');
            yearlyText.classList.remove('text-slate-900');
            yearlyText.classList.add('text-slate-500');
            
            // Update yearly note
            yearlyNote.innerHTML = '<span class="font-semibold">Save ₹2,398</span> with yearly billing';
        }
    });
});
</script>

    <!-- FAQ Section -->
    <section class="py-20 md:py-28 bg-gradient-to-b from-slate-50 to-white">
        <div class="container-custom">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-center mb-12 md:mb-16 tracking-tight animate-on-scroll">Frequently Asked Questions</h2>
            <div class="max-w-3xl mx-auto space-y-6">
                <!-- FAQ 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:shadow-md transition-all duration-300 animate-on-scroll">
                    <div class="flex items-start justify-between">
                        <h4 class="font-bold text-slate-900 mb-2 pr-8">Which social platforms do you support?</h4>
                        <i class="fas fa-chevron-down text-slate-400 mt-1 transition-transform duration-300"></i>
                    </div>
                    <p class="text-slate-600 text-sm mt-3 hidden">We currently support Instagram, Facebook, LinkedIn, and Twitter. We're working on adding TikTok and Pinterest by Q3 2024.</p>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:shadow-md transition-all duration-300 animate-on-scroll" style="animation-delay: 0.2s">
                    <div class="flex items-start justify-between">
                        <h4 class="font-bold text-slate-900 mb-2 pr-8">Can I track custom domain short URLs?</h4>
                        <i class="fas fa-chevron-down text-slate-400 mt-1 transition-transform duration-300"></i>
                    </div>
                    <p class="text-slate-600 text-sm mt-3 hidden">Yes! Pro and Business plans allow you to connect your own custom domains for fully branded short links.</p>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:shadow-md transition-all duration-300 animate-on-scroll" style="animation-delay: 0.4s">
                    <div class="flex items-start justify-between">
                        <h4 class="font-bold text-slate-900 mb-2 pr-8">Is there a limit on design storage?</h4>
                        <i class="fas fa-chevron-down text-slate-400 mt-1 transition-transform duration-300"></i>
                    </div>
                    <p class="text-slate-600 text-sm mt-3 hidden">Tracklio offers unlimited storage on Pro and Business plans. Starter plans include 5GB of cloud storage for your assets.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-20 md:py-28 relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 -skew-y-3 origin-bottom-right"></div>
        <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
        
        <div class="container-custom relative text-center text-white">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black mb-6 tracking-tight animate-on-scroll">Design. Publish. Track. All in One Place.</h2>
                <p class="text-xl mb-10 text-indigo-100 max-w-2xl mx-auto animate-on-scroll" style="animation-delay: 0.1s">Join over 10,000 teams and start your 14-day free trial today. No credit card required.</p>
                <div class="animate-on-scroll" style="animation-delay: 0.2s">
                    <a href="{{ route('register.page') }}" class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-full font-black text-lg hover:scale-105 hover:shadow-2xl transition-all duration-300 shadow-xl">
                        Get Started for Free
                    </a>
                    <p class="text-sm text-indigo-200 mt-4">No credit card required • Cancel anytime</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-10">
        <div class="container-custom">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 md:gap-12 mb-16">
                <div class="col-span-2 lg:col-span-2">
                    <!-- Tracklio Logo -->
                    <div class="flex items-center gap-3 mb-6">
                        <img
                            src="/assets/images/tracklio.png"
                            alt="Tracklio Logo"
                            class="h-10 w-10 object-contain"
                        >
                        <span class="text-2xl font-black text-white tracking-tight">
                            Tracklio
                        </span>
                    </div>
                    <p class="max-w-xs text-slate-400 leading-relaxed mb-6 text-sm">
5th Floor, Grand Emporio, Shiv Habitat B-Block, Motera Stadium Rd, opp. S Mall, Motera, Ahmedabad, Gujarat 380005                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 hover:bg-blue-500 hover:text-white transition-all hover:scale-110">
                            <i class="fab fa-twitter"></i>
                        </a>
                        
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 hover:bg-blue-600 hover:text-white transition-all hover:scale-110">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 hover:bg-gradient-to-r hover:from-purple-600 hover:via-pink-600 hover:to-yellow-500 hover:text-white transition-all hover:scale-110">
                            <i class="fab fa-instagram"></i>
                        </a>
                        
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 hover:bg-blue-700 hover:text-white transition-all hover:scale-110">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Product Column -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-xs">Product</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#features" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-sliders-h text-xs"></i>
                            Features
                        </a></li>
                        <li><a href="#pricing" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-tag text-xs"></i>
                            Pricing
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-code text-xs"></i>
                            API Docs
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-file-alt text-xs"></i>
                            Release Notes
                        </a></li>
                    </ul>
                </div>

                <!-- Company Column -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-xs">Company</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-building text-xs"></i>
                            About Us
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-briefcase text-xs"></i>
                            Careers
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-blog text-xs"></i>
                            Blog
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-envelope text-xs"></i>
                            Contact
                        </a></li>
                    </ul>
                </div>

                <!-- Legal Column -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-xs">Legal</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-shield-alt text-xs"></i>
                            Privacy Policy
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-file-contract text-xs"></i>
                            Terms of Service
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-cookie text-xs"></i>
                            Cookie Policy
                        </a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors flex items-center gap-2">
                            <i class="fas fa-lock text-xs"></i>
                            GDPR
                        </a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-sm text-slate-500">
                    <p>© 2024 Tracklio Technologies Inc. All rights reserved.</p>
                    <p class="text-xs mt-1">Made with <i class="fas fa-heart text-red-400"></i> for marketers and creators</p>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <span class="flex items-center gap-2 text-green-400">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span>All Systems Operational</span>
                    </span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-globe text-slate-500 text-sm"></i>
                        <select class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer text-slate-300">
                            <option class="bg-slate-800">English (US)</option>
                            <option class="bg-slate-800">Español</option>
                            <option class="bg-slate-800">Français</option>
                            <option class="bg-slate-800">Deutsch</option>
                            <option class="bg-slate-800">हिंदी</option>
                        </select>
                    </div>
                </div>
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
                mobileMenu.classList.toggle('animate-fade-in-down');
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
        const monthlyText = document.querySelector('#pricing .text-sm.font-semibold.text-slate-900');
        const yearlyText = document.querySelector('#pricing .text-sm.font-semibold.text-slate-500');
        
        const starterPrice = document.getElementById('starter-price');
        const proPrice = document.getElementById('pro-price');
        const businessPrice = document.getElementById('business-price');
        
        if (pricingToggle) {
            pricingToggle.addEventListener('click', function() {
                if (toggleCircle.classList.contains('left-1')) {
                    toggleCircle.classList.remove('left-1');
                    toggleCircle.classList.add('left-8');
                    monthlyText.classList.remove('text-slate-900');
                    monthlyText.classList.add('text-slate-500');
                    yearlyText.classList.remove('text-slate-500');
                    yearlyText.classList.add('text-slate-900');
                    
                    // Update prices
                    starterPrice.textContent = '₹1,199';
                    proPrice.textContent = '₹3,199';
                    businessPrice.textContent = '₹6,399';
                    
                    // Add animation effect
                    [starterPrice, proPrice, businessPrice].forEach(el => {
                        el.classList.add('scale-110');
                        setTimeout(() => el.classList.remove('scale-110'), 300);
                    });
                } else {
                    toggleCircle.classList.remove('left-8');
                    toggleCircle.classList.add('left-1');
                    monthlyText.classList.remove('text-slate-500');
                    monthlyText.classList.add('text-slate-900');
                    yearlyText.classList.remove('text-slate-900');
                    yearlyText.classList.add('text-slate-500');
                    
                    // Update prices
                    starterPrice.textContent = '₹1,499';
                    proPrice.textContent = '₹3,999';
                    businessPrice.textContent = '₹7,999';
                    
                    // Add animation effect
                    [starterPrice, proPrice, businessPrice].forEach(el => {
                        el.classList.add('scale-110');
                        setTimeout(() => el.classList.remove('scale-110'), 300);
                    });
                }
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                
                // Skip if it's just "#"
                if (href === '#') return;
                
                e.preventDefault();
                
                const targetElement = document.querySelector(href);
                if (targetElement) {
                    // Close mobile menu if open
                    mobileMenu.classList.add('hidden');
                    
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Plan selection buttons
        document.querySelectorAll('.relative.p-8 button, .relative.animate-on-scroll button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const planCard = this.closest('.p-8');
                const planName = planCard.querySelector('h3').textContent;
                
                // Add visual feedback
                this.classList.add('scale-95');
                setTimeout(() => this.classList.remove('scale-95'), 200);
                
                // Show notification (in production, redirect to signup)
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-down';
                notification.textContent = `Redirecting to ${planName} signup...`;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'translate-x-full', 'transition-all', 'duration-300');
                    setTimeout(() => notification.remove(), 300);
                }, 2000);
                
                // In production, redirect to signup page
                // window.location.href = "{{ route('register.page') }}?plan=" + planName.toLowerCase();
            });
        });
        
        // FAQ accordion
        document.querySelectorAll('.bg-white.p-6.rounded-2xl').forEach(faq => {
            const question = faq.querySelector('.flex.items-start.justify-between');
            const answer = faq.querySelector('p.text-slate-600');
            const icon = faq.querySelector('i.fa-chevron-down');
            
            if (question && answer && icon) {
                question.addEventListener('click', () => {
                    answer.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                    
                    // Close other FAQs
                    document.querySelectorAll('.bg-white.p-6.rounded-2xl').forEach(otherFaq => {
                        if (otherFaq !== faq) {
                            const otherAnswer = otherFaq.querySelector('p.text-slate-600');
                            const otherIcon = otherFaq.querySelector('i.fa-chevron-down');
                            if (otherAnswer && otherIcon) {
                                otherAnswer.classList.add('hidden');
                                otherIcon.classList.remove('rotate-180');
                            }
                        }
                    });
                });
            }
        });
        
        // View Demo button
        const viewDemoBtn = document.getElementById('view-demo-btn');
        if (viewDemoBtn) {
            viewDemoBtn.addEventListener('click', function() {
                // Scroll to dashboard image with highlight effect
                const dashboard = document.querySelector('.relative.max-w-5xl.mx-auto.group');
                if (dashboard) {
                    dashboard.scrollIntoView({ behavior: 'smooth' });
                    
                    // Add highlight effect
                    dashboard.classList.add('scale-105');
                    setTimeout(() => dashboard.classList.remove('scale-105'), 1000);
                    
                    // Pulse animation
                    const overlay = document.createElement('div');
                    overlay.className = 'absolute inset-0 bg-yellow-400/20 rounded-2xl pointer-events-none animate-pulse';
                    dashboard.querySelector('.relative').appendChild(overlay);
                    
                    setTimeout(() => overlay.remove(), 2000);
                }
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
        let lastScrollTop = 0;
        const navbar = document.querySelector('nav');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 100) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
            
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down
                navbar.classList.add('-translate-y-full');
            } else {
                // Scrolling up
                navbar.classList.remove('-translate-y-full');
            }
            
            lastScrollTop = scrollTop;
        });
        
        // Add loading state to buttons
        document.querySelectorAll('button, a[href*="register"]').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.getAttribute('href') && this.getAttribute('href').includes('register')) {
                    // Add loading spinner
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
                    this.disabled = true;
                    
                    // Revert after 2 seconds (in production, remove this and let the page redirect)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });
    </script>
</body>
</html>