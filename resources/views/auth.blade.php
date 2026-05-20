<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tracklio - Login / Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- HEADER (Same as Landing Page) -->
<nav class="fixed top-0 w-full bg-white/80 backdrop-blur-sm border-b border-slate-200 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- LOGO -->
            <div class="flex items-center gap-2">
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <img
                        src="{{ asset('assets/images/tracklio.png') }}"
                        alt="Tracklio Logo"
                        class="h-9 w-9 object-contain"
                    >
                    <span class="text-2xl font-bold text-indigo-600">
                        Tracklio
                    </span>
                </a>
            </div>

            <!-- MENU -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}#features"
                   class="text-slate-600 hover:text-slate-900 font-medium">
                    Features
                </a>

                <a href="{{ route('landing') }}#how-it-works"
                   class="text-slate-600 hover:text-slate-900 font-medium">
                    How it works
                </a>

                <a href="{{ route('landing') }}#pricing"
                   class="text-slate-600 hover:text-slate-900 font-medium">
                    Pricing
                </a>

                <a href="{{ route('login') }}"
                   class="text-slate-600 hover:text-slate-900 font-medium">
                    Sign in
                </a>

                <a href="{{ route('register.page') }}"
                   class="px-6 py-2 bg-indigo-600 text-white rounded-full font-medium hover:bg-indigo-700 transition-colors">
                    Start Free Trial
                </a>
            </div>

            <!-- MOBILE ICON (static, no JS needed) -->
            <div class="md:hidden">
                <svg class="h-6 w-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </div>

        </div>
    </div>
</nav>

<!-- OFFSET FOR FIXED HEADER -->
<div class="pt-16"></div>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }
        .brand-gradient {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
        }
        .btn-primary {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
        }
        .input-field {
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .side-image {
            background: linear-gradient(rgba(79, 70, 229, 0.9), rgba(124, 58, 237, 0.9)), 
                        url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body x-data="{
    mode: '{{ isset($token) ? 'reset' : (request()->routeIs('register.page') ? 'register' : 'login') }}',
    showPassword: false,
    showConfirmPassword: false
}"
class="bg-gray-50 text-gray-800">


<div class="min-h-screen flex">
    <!-- Left Side - Image/Info -->
    <div class="hidden lg:flex lg:w-1/2 side-image text-white p-12 flex-col justify-between">
        <div>
            <!-- <div class="flex items-center gap-3 mb-12">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <i class="fas fa-th-large text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold">Tracklio</span>
            </div> -->
            
            <div class="max-w-md">
                <h1 class="text-4xl font-bold mb-6">Design. Publish. Track.</h1>
                <p class="text-lg text-white/90 mb-8 leading-relaxed">
                    Join thousands of teams who use Tracklio to manage their design assets, 
                    publish social content, and track performance from a single dashboard.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">All-in-one Platform</h4>
                            <p class="text-sm text-white/80">Everything you need in one place</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Real-time Analytics</h4>
                            <p class="text-sm text-white/80">Track performance with detailed insights</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Team Collaboration</h4>
                            <p class="text-sm text-white/80">Work together seamlessly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-white/80 text-sm">
            <p>© 2024 Tracklio. All rights reserved.</p>
        </div>
    </div>

    <!-- Right Side - Auth Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <div class="flex items-center gap-3">
                    <div class="brand-gradient w-12 h-12 rounded-xl flex items-center justify-center">
                        <i class="fas fa-th-large text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">Tracklio</span>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Tabs -->
                <div class="flex border-b mb-8">
                    <button 
                        @click="mode = 'login'"
                        :class="mode === 'login' ? 'border-b-2 border-indigo-600 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-4 text-center font-medium text-sm"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                    <button 
                        @click="mode = 'register'"
                        :class="mode === 'register' ? 'border-b-2 border-indigo-600 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-4 text-center font-medium text-sm"
                    >
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </button>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <h4 class="font-semibold text-red-700">Please fix the following:</h4>
                    </div>
                    <ul class="mt-2 text-sm text-red-600 space-y-1 pl-8">
                        @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
<!-- FORGOT PASSWORD FORM -->
<div x-show="mode === 'forgot'" x-transition class="space-y-6">

    <h2 class="text-xl font-bold text-center mb-6">Forgot Password</h2>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">Email Address</label>
            <input type="email" name="email" required
                   class="input-field w-full rounded-xl py-3.5 px-4">
        </div>

        <button type="submit" class="btn-primary w-full text-white py-3.5 rounded-xl font-semibold">
            Send Reset Link
        </button>
    </form>

    <p class="text-sm text-center">
        <a href="#" @click.prevent="mode = 'login'" class="text-indigo-600 font-semibold">
            Back to Login
        </a>
    </p>
</div>
@if(isset($token))
<div x-show="mode === 'reset'" x-transition class="space-y-6">

    <h2 class="text-xl font-bold text-center mb-6">Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <input type="email" name="email" value="{{ $email }}" required
                   class="input-field w-full rounded-xl py-3.5 px-4">
        </div>

        <div>
            <input type="password" name="password" placeholder="New Password" required
                   class="input-field w-full rounded-xl py-3.5 px-4">
        </div>

        <div>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                   class="input-field w-full rounded-xl py-3.5 px-4">
        </div>

        <button type="submit" class="btn-primary w-full text-white py-3.5 rounded-xl font-semibold">
            Reset Password
        </button>
    </form>
</div>
@endif

                <!-- LOGIN FORM -->
                <div x-show="mode === 'login'" x-transition>
                    <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                name="email" 
                                required
                                placeholder="Enter your email"
                                class="input-field w-full rounded-xl py-3.5 px-4 focus:outline-none placeholder-gray-400"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input 
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password" 
                                    required
                                    placeholder="Enter your password"
                                    class="input-field w-full rounded-xl py-3.5 px-4 pr-12 focus:outline-none placeholder-gray-400"
                                >
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700"
                                >
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="remember" class="rounded border-gray-300 text-indigo-600">
                                    <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                                </div>
<a href="#" @click.prevent="mode = 'forgot'"
   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
   Forgot password?
</a>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full text-white py-3.5 rounded-xl font-semibold">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                        </button>
                    </form>
                </div>

                <!-- REGISTER FORM -->
                <div x-show="mode === 'register'" x-transition>
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                required
                                placeholder="Enter your full name"
                                class="input-field w-full rounded-xl py-3.5 px-4 focus:outline-none placeholder-gray-400"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                name="email" 
                                required
                                placeholder="Enter your email"
                                class="input-field w-full rounded-xl py-3.5 px-4 focus:outline-none placeholder-gray-400"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input 
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password" 
                                    required
                                    placeholder="Create a strong password"
                                    class="input-field w-full rounded-xl py-3.5 px-4 pr-12 focus:outline-none placeholder-gray-400"
                                >
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700"
                                >
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <input 
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    name="password_confirmation" 
                                    required
                                    placeholder="Confirm your password"
                                    class="input-field w-full rounded-xl py-3.5 px-4 pr-12 focus:outline-none placeholder-gray-400"
                                >
                                <button 
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700"
                                >
                                    <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms" required class="mt-1 rounded border-gray-300 text-indigo-600">
                            <label for="terms" class="text-sm text-gray-600">
                                I agree to the 
                                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Terms of Service</a> 
                                and 
                                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="btn-primary w-full text-white py-3.5 rounded-xl font-semibold">
                            <i class="fas fa-user-plus mr-2"></i> Create Account
                        </button>
                    </form>
                </div>

                <!-- Divider -->
                <div class="my-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>
                    
                    <!-- <div class="mt-6 grid grid-cols-2 gap-3">
                        <button type="button" class="flex items-center justify-center gap-3 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fab fa-google text-red-500"></i>
                            <span class="text-sm font-medium text-gray-700">Google</span>
                        </button>
                        <button type="button" class="flex items-center justify-center gap-3 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fab fa-github text-gray-800"></i>
                            <span class="text-sm font-medium text-gray-700">GitHub</span>
                        </button>
                    </div>
                </div> -->

                <!-- Switch Links -->
                <div class="text-center pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-600" x-show="mode === 'login'">
                        Don't have an account?
                        <a href="{{ route('register.page') }}" 
                           @click.prevent="mode = 'register'"
                           class="text-indigo-600 hover:text-indigo-800 font-semibold ml-1">
                            Sign up here
                        </a>
                    </p>
                    <p class="text-sm text-gray-600" x-show="mode === 'register'">
                        Already have an account?
                        <a href="{{ route('login') }}" 
                           @click.prevent="mode = 'login'"
                           class="text-indigo-600 hover:text-indigo-800 font-semibold ml-1">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>

            <!-- Security Note -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p><i class="fas fa-shield-alt mr-1"></i> Secure authentication • 256-bit encryption</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>